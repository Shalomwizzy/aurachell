<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Thank you for your Aurachell order');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.thank-you');
    }
}
