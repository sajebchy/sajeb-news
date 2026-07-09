<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\PushSubscription;
use App\Models\SeoSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendPushNotifications extends Command
{
    protected $signature = 'notifications:send-push {news_id?}';
    protected $description = 'Send push notifications to all subscribers about a news post';

    public function handle()
    {
        $newsId = $this->argument('news_id');

        if (!$newsId) {
            $this->info("Usage: php artisan notifications:send-push {news_id}");
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

        $settings = SeoSetting::first();
        if (!$settings?->vapid_public_key || !$settings?->vapid_private_key) {
            $this->error("VAPID keys not configured. Set them in Admin > Site Settings.");
            return Command::FAILURE;
        }

        $total = PushSubscription::active()->count();
        if ($total === 0) {
            $this->error("No active subscriptions found.");
            return Command::FAILURE;
        }

        $this->info("Sending push for: {$news->title}");
        $this->info("Active subscribers: {$total}");

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:' . ($settings->office_email ?? 'admin@sajeb.news'),
                'publicKey' => $settings->vapid_public_key,
                'privateKey' => $settings->vapid_private_key,
            ],
        ];

        $webPush = new WebPush($auth);

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
        $bar = $this->output->createProgressBar($total);

        foreach ($webPush->flush() as $report) {
            $bar->advance();

            if ($report->isSuccess()) {
                $sent++;
            } else {
                $failed++;
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $report->getEndpoint())
                        ->update(['is_active' => false]);
                    $this->newLine();
                    $this->warn("  Expired subscription deactivated.");
                }
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! Sent: {$sent}, Failed: {$failed}");

        return Command::SUCCESS;
    }
}
