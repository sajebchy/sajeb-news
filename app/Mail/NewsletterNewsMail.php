<?php

namespace App\Mail;

use App\Models\News;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterNewsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public News $news,
        public NewsletterSubscriber $subscriber
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'নতুন খবর: ' . $this->news->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.new-news',
        );
    }
}
