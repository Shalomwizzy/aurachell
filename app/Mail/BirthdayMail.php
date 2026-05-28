<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BirthdayMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $couponCode = null,
        public ?string $discountDescription = null,
        public int $couponDays = 7,
    ) {}

    public function envelope(): Envelope
    {
        $firstName = explode(' ', $this->user->name)[0];
        return new Envelope(subject: "Happy Birthday, {$firstName} — a gift from Aurachell");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.birthday');
    }
}
