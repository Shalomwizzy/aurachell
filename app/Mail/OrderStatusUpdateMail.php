<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $statusLabel;

    public string $statusMessage;

    public function __construct(public Order $order)
    {
        $map = [
            'processing' => ['Processing',       'We\'ve received your payment and are now preparing your order.'],
            'packed' => ['Packed & Ready',   'Your order has been carefully packed and is ready for dispatch.'],
            'out_for_delivery' => ['Out for Delivery', 'Your order is out for delivery and will reach you today.'],
            'cancelled' => ['Cancelled',        'Your order has been cancelled. If you paid, a refund will be processed shortly.'],
            'refunded' => ['Refunded',         'Your refund has been processed. Please allow 3–5 business days for it to reflect.'],
        ];

        $this->statusLabel = $map[$order->status][0] ?? ucfirst(str_replace('_', ' ', $order->status));
        $this->statusMessage = $map[$order->status][1] ?? 'Your order status has been updated.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Update: '.$this->statusLabel.' — '.$this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status-update');
    }
}
