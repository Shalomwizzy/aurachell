<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        $prefix = $this->order->payment_status === 'paid' ? '✅ Payment Confirmed' : '🛒 New Order Placed';

        return new Envelope(
            subject: $prefix.' — #'.$this->order->order_number.' — ₦'.number_format($this->order->total, 0),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-order-notification',
        );
    }
}
