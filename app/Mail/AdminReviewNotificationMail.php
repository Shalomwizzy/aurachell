<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminReviewNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Review $review, public bool $isUpdate = false) {}

    public function envelope(): Envelope
    {
        $verb = $this->isUpdate ? 'Updated' : 'New';

        return new Envelope(
            subject: '⭐ '.$verb.' Review — '.$this->review->rating.'★ on '.($this->review->product->name ?? 'a product'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-review-notification',
        );
    }
}
