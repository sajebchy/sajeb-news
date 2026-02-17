<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\PushSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPushNotifications extends Command
{
    protected $signature = 'notifications:send-push {news_id}';
    protected $description = 'Send push notifications to all subscribers about a new post';

    public function handle()
    {
        $newsId = $this->argument('news_id');
        $news = News::find($newsId);

        if (!$news) {
            $this->error("News with ID {$newsId} not found.");
            return Command::FAILURE;
        }

        $this->info("Sending push notifications for: {$news->title}");

        // Get all active subscriptions
        $subscriptions = PushSubscription::active()->get();
        $total = $subscriptions->count();
        $sent = 0;
        $failed = 0;

        $this->output->progressStart($total);

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendNotificationToSubscriber($subscription, $news);
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::warning("Failed to send notification to {$subscription->endpoint}: " . $e->getMessage());
                
                // If subscription is invalid, deactivate it
                if (str_contains($e->getMessage(), '410') || str_contains($e->getMessage(), '404')) {
                    $subscription->deactivate();
                }
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->line('');
        $this->info("Push notifications sent successfully!");
        $this->line("Total subscriptions: $total");
        $this->line("Sent: $sent");
        $this->line("Failed: $failed");

        return Command::SUCCESS;
    }

    private function sendNotificationToSubscriber($subscription, $news)
    {
        // In a real implementation, you would use a library like WebPush
        // For now, this is a placeholder that shows the structure

        $title = 'নতুন নিউজ: ' . substr($news->title, 0, 50) . '...';
        $body = substr(strip_tags($news->content), 0, 100) . '...';
        $url = route('news.show', $news->slug);

        // Payload for the notification
        $payload = [
            'title' => $title,
            'body' => $body,
            'icon' => config('app.url') . '/images/logo.png',
            'badge' => config('app.url') . '/images/badge.png',
            'url' => $url,
            'tag' => 'news-' . $news->id,
        ];

        // Log the notification (in real implementation, send via WebPush library)
        Log::info('Push notification prepared', [
            'subscription_endpoint' => substr($subscription->endpoint, 0, 50) . '...',
            'news_id' => $news->id,
            'title' => $title,
        ]);

        return true;
    }
}
