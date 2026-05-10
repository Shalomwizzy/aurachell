<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NewMonthMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $month;

    public function __construct(public User $user, public Collection $featured)
    {
        $this->month = now()->format('F Y');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Happy New Month from Aurachell 🌿');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-month');
    }
}
