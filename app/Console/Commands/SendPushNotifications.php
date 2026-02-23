<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\PushSubscription;
use App\Models\SeoSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendPushNotifications extends Command
{
    protected $signature = 'notifications:send-push {news_id?}';
    protected $description = 'Send push notifications to all subscribers about a news post';

    public function handle()
    {
        $newsId = $this->argument('news_id');
        
        if (!$newsId) {
            $this->info("No news ID provided. Usage: php artisan notifications:send-push {news_id}");
            return Command::FAILURE;
        }

        $news = News::find($newsId);

        if (!$news) {
            $this->error("News with ID {$newsId} not found.");
            return Command::FAILURE;
        }

        if ($news->status !== 'published') {
            $this->error("News must be published to send notifications.");
            return Command::FAILURE;
        }

        $this->info("Sending push notifications for: {$news->title}");

        // Get VAPID keys
        $settings = SeoSetting::first();
        if (!$settings?->vapid_public_key || !$settings?->vapid_private_key) {
            $this->error("VAPID keys not configured. Please configure them in Site Settings.");
            return Command::FAILURE;
        }

        // Get all active subscriptions
        $subscriptions = PushSubscription::active()->cursor();
        $total = PushSubscription::active()->count();
        
        if ($total === 0) {
            $this->error("No active subscriptions found.");
            return Command::FAILURE;
        }

        $sent = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar($total);

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendNotificationToSubscriber($subscription, $news, $settings);
                $sent++;
                $this->line('');
                $this->info("✓ Sent to: " . substr($subscription->endpoint, 0, 50) . "...");
            } catch (\Exception $e) {
                $failed++;
                $this->line('');
                $this->error("✗ Failed: " . $e->getMessage());
                Log::warning("Failed to send notification to {$subscription->endpoint}: " . $e->getMessage());
                
                // If subscription endpoint is invalid, deactivate it
                if (str_contains($e->getMessage(), '410') || str_contains($e->getMessage(), '404')) {
                    $subscription->deactivate();
                    $this->warn("  Subscription deactivated.");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✓ Push notifications finished!");
        $this->line("<info>Total subscriptions: {$total}</info>");
        $this->line("<info>Sent: {$sent}</info>");
        if ($failed > 0) {
            $this->line("<error>Failed: {$failed}</error>");
        }

        return Command::SUCCESS;
    }

    private function sendNotificationToSubscriber($subscription, $news, $settings)
    {
        // Prepare notification payload
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

        // Send via Web Push Protocol
        $response = Http::timeout(10)
            ->asJson()
            ->withHeaders([
                'TTL' => '24',
                'Urgency' => 'high',
                'Content-Type' => 'application/octet-stream',
                'Content-Encoding' => 'aes128gcm',
            ])
            ->post($subscription->endpoint, $payload);

        if (!$response->successful()) {
            $status = $response->status();
            
            if ($status === 410 || $status === 404) {
                $subscription->deactivate();
                throw new \Exception("Endpoint invalid (status {$status}) - subscription deactivated");
            }
            
            throw new \Exception("Web Push API error (status {$status}): " . $response->body());
        }

        Log::info('Push notification sent', [
            'subscription_id' => $subscription->id,
            'news_id' => $news->id,
            'title' => $title,
        ]);

        return true;
    }
}

