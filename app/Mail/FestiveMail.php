<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FestiveMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $greeting;

    public string $message;

    public string $ctaText;

    public function __construct(public User $user, public string $event)
    {
        $map = [
            'christmas' => ['Merry Christmas', 'Wishing you warmth, light, and the finest scents this festive season.', 'Shop Christmas Gifts'],
            'easter' => ['Happy Easter', 'May this season bring renewal, joy, and beautiful scents to your home.', 'Explore Easter Collection'],
            'eid' => ['Eid Mubarak', 'May this blessed occasion fill your home with peace, love, and the finest aromas.', 'Shop Eid Gifts'],
            'ramadan' => ['Ramadan Kareem', 'Wishing you a blessed month filled with peace and the most uplifting scents.', 'Discover Ramadan Picks'],
            'new_year' => ['Happy New Year', 'A fresh year deserves a fresh scent. Welcome the new year with Aurachell.', 'Start the Year Right'],
        ];
        $this->greeting = $map[$event][0] ?? 'Greetings';
        $this->message = $map[$event][1] ?? 'Wishing you all the best from Aurachell.';
        $this->ctaText = $map[$event][2] ?? 'Shop Now';
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->greeting.' from Aurachell');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.festive');
    }
}
