<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WishlistReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Collection $items) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Aurachell wishlist is waiting for you');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.wishlist-reminder');
    }
}
