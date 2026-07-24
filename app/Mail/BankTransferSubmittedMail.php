<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BankTransferSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Bank Transfer Proof Received — {$this->order->order_number}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.bank-transfer-submitted');
    }

    public function attachments(): array
    {
        $bt = $this->order->bankTransfer;

        if (! $bt || ! $bt->proof_path) {
            return [];
        }

        $path = storage_path('app/bank-transfer-proofs/' . $bt->proof_path);

        if (! is_file($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)->as($bt->proof_original_name ?: $bt->proof_path),
        ];
    }
}
