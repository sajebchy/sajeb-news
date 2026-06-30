<?php

namespace App\Jobs;

use App\Mail\NewsletterNewsMail;
use App\Models\News;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public int $newsId) {}

    public function handle(): void
    {
        $news = News::with(['category', 'author'])->find($this->newsId);

        if (!$news || $news->status !== 'published') {
            Log::warning("SendNewsletterEmail: news #{$this->newsId} not found or not published.");
            return;
        }

        $subscribers = NewsletterSubscriber::where('is_verified', true)
            ->whereNull('unsubscribed_at')
            ->cursor();

        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)
                    ->send(new NewsletterNewsMail($news, $subscriber));
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                Log::warning("Newsletter email failed to {$subscriber->email}: " . $e->getMessage());
            }
        }

        Log::info("Newsletter emails dispatched for news #{$news->id} — sent: {$sent}, failed: {$failed}");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("SendNewsletterEmail job failed for news #{$this->newsId}: " . $e->getMessage());
    }
}
