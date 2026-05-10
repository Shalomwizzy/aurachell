<?php

namespace App\Mail;

use App\Models\ReturnRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $statusLabel;

    public string $statusMessage;

    public function __construct(
        public ReturnRequest $returnRequest,
        public string $event, // submitted | approved | rejected | refunded
    ) {
        $map = [
            'submitted' => ['Return Request Received',  'We\'ve received your return request and will review it within 2–3 business days.'],
            'approved' => ['Return Request Approved',  'Great news — your return request has been approved. Please follow the instructions below.'],
            'rejected' => ['Return Request Rejected',  'After reviewing your request, we\'re unable to process this return. See details below.'],
            'refunded' => ['Refund Processed',         'Your refund has been processed. Please allow 3–5 business days for it to reflect in your account.'],
        ];

        $this->statusLabel = $map[$event][0] ?? 'Return Update';
        $this->statusMessage = $map[$event][1] ?? 'Your return request status has been updated.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->statusLabel.' — '.$this->returnRequest->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.return-request');
    }
}
