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
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendNewsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newsId;
    public $tries = 3;
    public $backoff = [10, 60, 300];

    public function __construct($newsId)
    {
        $this->newsId = $newsId;
    }

    public function handle(): void
    {
        $news = News::find($this->newsId);

        if (!$news || $news->status !== 'published') {
            Log::warning("News not found or not published for push - ID: {$this->newsId}");
            return;
        }

        $settings = SeoSetting::first();
        if (!$settings?->vapid_public_key || !$settings?->vapid_private_key) {
            Log::error("VAPID keys not configured - cannot send push for news ID: {$this->newsId}");
            return;
        }

        $activeCount = PushSubscription::active()->count();
        if ($activeCount === 0) {
            Log::info("No active push subscriptions for news ID: {$this->newsId}");
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:' . ($settings->office_email ?? 'admin@sajeb.news'),
                'publicKey' => $settings->vapid_public_key,
                'privateKey' => $settings->vapid_private_key,
            ],
        ];

        try {
            $webPush = new WebPush($auth);
        } catch (\Exception $e) {
            Log::error("WebPush init failed: " . $e->getMessage());
            return;
        }

        $payload = json_encode([
            'title' => mb_substr($news->title, 0, 60),
            'body' => mb_substr(strip_tags($news->excerpt ?? $news->content ?? ''), 0, 120),
            'icon' => $news->featured_image
                ? asset('storage/' . $news->featured_image)
                : asset('images/logo.png'),
            'badge' => asset('images/badge.png'),
            'tag' => 'news-' . $news->id,
            'data' => [
                'url' => route('news.show', $news->slug),
                'news_id' => $news->id,
            ],
        ]);

        $subscriptions = PushSubscription::active()->cursor();

        foreach ($subscriptions as $sub) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                ]),
                $payload
            );
        }

        $sent = 0;
        $failed = 0;

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $sent++;
            } else {
                $failed++;
                $endpoint = $report->getEndpoint();

                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $endpoint)->update(['is_active' => false]);
                }

                Log::warning("Push failed: {$report->getReason()}", [
                    'endpoint' => substr($endpoint, 0, 60),
                ]);
            }
        }

        Log::info("Push notifications sent for news #{$this->newsId}", [
            'title' => $news->title,
            'sent' => $sent,
            'failed' => $failed,
            'total_subscribers' => $activeCount,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendNewsNotification job failed for news ID {$this->newsId}: " . $exception->getMessage());
    }
}
