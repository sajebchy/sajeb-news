<?php

namespace App\Jobs;

use App\Models\News;
use App\Models\PushSubscription;
use App\Models\SeoSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendNewsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newsId;
    public $tries = 3;
    public $backoff = [10, 60, 300];

    /**
     * Create a new job instance.
     */
    public function __construct($newsId)
    {
        $this->newsId = $newsId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $news = News::find($this->newsId);

        if (!$news || $news->status !== 'published') {
            Log::warning("News not found or not published - Job abandoned for ID: {$this->newsId}");
            return;
        }

        // Get VAPID keys
        $settings = SeoSetting::first();
        if (!$settings?->vapid_public_key || !$settings?->vapid_private_key) {
            Log::error("VAPID keys not configured - cannot send push notifications for news ID: {$this->newsId}");
            return;
        }

        // Get all active subscriptions
        $subscriptions = PushSubscription::active()->cursor();
        $sent = 0;
        $failed = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendPushToSubscriber($subscription, $news);
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::warning("Failed to send push to subscriber: " . $e->getMessage(), [
                    'news_id' => $news->id,
                    'subscription_id' => $subscription->id,
                ]);

                // Deactivate invalid subscriptions
                if (str_contains($e->getMessage(), '410') || str_contains($e->getMessage(), '404')) {
                    try {
                        $subscription->deactivate();
                    } catch (\Exception $deactivateError) {
                        Log::error("Failed to deactivate subscription: " . $deactivateError->getMessage());
                    }
                }
            }
        }

        Log::info("Push notifications sent for news", [
            'news_id' => $news->id,
            'sent' => $sent,
            'failed' => $failed,
            'title' => $news->title,
        ]);
    }

    /**
     * Send push notification to a single subscriber
     */
    private function sendPushToSubscriber(PushSubscription $subscription, News $news): void
    {
        $settings = SeoSetting::first();
        if (!$settings?->vapid_private_key || !$settings?->vapid_public_key) {
            throw new \Exception("VAPID keys not configured");
        }

        $title = 'নতুন নিউজ: ' . substr($news->title, 0, 50);
        $body = substr(strip_tags($news->content ?? $news->excerpt), 0, 100);
        $icon = $news->featured_image 
            ? asset('storage/' . $news->featured_image)
            : asset('images/logo.png');

        $payload = [
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
            'badge' => asset('images/badge.png'),
            'tag' => 'news-' . $news->id,
            'requireInteraction' => false,
            'data' => [
                'url' => route('news.show', $news->slug),
                'news_id' => $news->id,
                'news_title' => $news->title,
            ],
        ];

        try {
            // Parse endpoint
            $endpoint = $subscription->endpoint;
            $urlParts = parse_url($endpoint);
            $host = $urlParts['host'] ?? 'push.example.com';

            // Generate VAPID JWT token
            $now = time();
            $orgDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
            
            $header = [
                'typ' => 'JWT',
                'alg' => 'ES256',
            ];

            $claims = [
                'aud' => "https://{$host}",
                'exp' => $now + 43200, // 12 hours
                'sub' => 'mailto:admin@' . $orgDomain,
            ];

            // Create JWT (this uses VAPID Elliptic Curve signing)
            $jwt = $this->createVapidJwt($header, $claims, $settings->vapid_private_key);
            $publicKey = $settings->vapid_public_key;

            // Send with Web Push Protocol (RFC 8188)
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'vapid t=' . $jwt . ', k=' . $publicKey,
                    'TTL' => '86400', // 24 hours
                    'Urgency' => 'high',
                    'Content-Encoding' => 'aes128gcm',
                ])
                ->post($endpoint, json_encode($payload));

            if (!$response->successful()) {
                $status = $response->status();
                
                if ($status === 410 || $status === 404) {
                    $subscription->deactivate();
                    throw new \Exception("Endpoint invalid (HTTP {$status})");
                }
                
                throw new \Exception("Push request failed (HTTP {$status})");
            }

        } catch (\Exception $e) {
            Log::error("Push send error for subscription {$subscription->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Create VAPID JWT token
     * Works with private keys in PKCS8 format
     */
    private function createVapidJwt(array $header, array $claims, string $privateKeyPem): string
    {
        // Prepare segments
        $headerEncoded = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $claimsEncoded = rtrim(strtr(base64_encode(json_encode($claims)), '+/', '-_'), '=');
        $signingInput = $headerEncoded . '.' . $claimsEncoded;

        // Sign with OpenSSL (supports EC keys)
        $signature = '';
        $success = openssl_sign(
            $signingInput,
            $signature,
            $privateKeyPem,
            'sha256'
        );

        if (!$success) {
            throw new \Exception('Failed to sign JWT with VAPID private key');
        }

        // URL-safe base64 encode signature
        $signatureEncoded = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $signingInput . '.' . $signatureEncoded;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SendNewsNotification job failed for news ID {$this->newsId}", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
