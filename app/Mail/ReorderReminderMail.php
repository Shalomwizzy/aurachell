<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReorderReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public ?Order $order = null) {}

    public function envelope(): Envelope
    {
        $firstName = explode(' ', $this->user->name)[0];
        return new Envelope(subject: "Time to refresh your Aurachell, {$firstName}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reorder-reminder');
    }
}
