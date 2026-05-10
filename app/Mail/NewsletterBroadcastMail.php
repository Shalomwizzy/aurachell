<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterBroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailBody;

    public string $recipientName;

    private string $mailSubject;

    public function __construct(string $mailSubject, string $body, string $recipientName = '')
    {
        $this->mailSubject = $mailSubject;
        $this->emailBody = $body;
        $this->recipientName = $recipientName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-broadcast',
            with: ['subject' => $this->mailSubject],
        );
    }
}
