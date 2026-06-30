<?php

namespace App\Observers;

use App\Models\News;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        // Do nothing on creation, wait for publish
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        // Check if news was just published (status changed to published)
        if ($news->wasChanged('status') && $news->status === 'published' && $news->published_at) {
            $this->sendPushNotification($news);
        }
    }

    /**
     * Handle the News "deleted" event.
     */
    public function deleted(News $news): void
    {
        // Nothing to do on delete
    }

    /**
     * Handle the News "restored" event.
     */
    public function restored(News $news): void
    {
        // Nothing to do on restore
    }

    /**
     * Handle the News "force deleted" event.
     */
    public function forceDeleted(News $news): void
    {
        // Nothing to do on force delete
    }

    /**
     * Send push notifications to all active subscribers
     */
    private function sendPushNotification(News $news): void
    {
        // Check if there are any active subscriptions
        $activeCount = PushSubscription::active()->count();
        
        if ($activeCount === 0) {
            Log::info("No active push subscriptions to notify for news ID: {$news->id}");
            return;
        }

        try {
            // Dispatch the command as a background job for better performance
            // This prevents the news creation/update from being slow
            Queue::job(new \App\Jobs\SendNewsNotification($news->id))
                ->onConnection('sync')
                ->dispatch();

            Log::info("Push notification job queued for news ID: {$news->id}");
        } catch (\Exception $e) {
            Log::error("Failed to queue push notifications for news ID {$news->id}: " . $e->getMessage());
        }
    }
}
