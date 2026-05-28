<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CartAbandonmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Collection $items,
        public int $stage = 1,
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            1 => 'Your cart is saving your spot — Aurachell',
            2 => 'Your space is still waiting for this — Aurachell',
            3 => 'Your cart expires soon — ' . $this->items->count() . ' item' . ($this->items->count() === 1 ? '' : 's') . ' at risk',
        ];

        return new Envelope(subject: $subjects[$this->stage] ?? $subjects[1]);
    }

    public function content(): Content
    {
        $views = [
            1 => 'emails.cart-abandonment',
            2 => 'emails.cart-abandonment-2',
            3 => 'emails.cart-abandonment-3',
        ];

        return new Content(view: $views[$this->stage] ?? $views[1]);
    }
}
