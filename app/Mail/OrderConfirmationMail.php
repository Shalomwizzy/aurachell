<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed — '.$this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    public function attachments(): array
    {
        try {
            $this->order->loadMissing('items.product');
            $pdf = app(Pdf::class)
                ->loadView('admin.orders.invoice', ['order' => $this->order]);

            return [
                Attachment::fromData(
                    fn () => $pdf->output(),
                    'invoice-'.$this->order->order_number.'.pdf'
                )->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            Log::warning('Invoice PDF attachment failed for '.$this->order->order_number.': '.$e->getMessage());

            return [];
        }
    }
}
