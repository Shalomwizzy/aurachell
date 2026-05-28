<?php

namespace App\Console\Commands;

use App\Mail\ReviewRequestMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReviewRequest extends Command
{
    protected $signature = 'emails:review-request';

    protected $description = 'Send review request emails to customers 7 days after delivery';

    public function handle(): int
    {
        $orders = Order::with(['user', 'items'])
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [
                now()->subDays(8),
                now()->subDays(7),
            ])
            ->whereHas('user')
            ->get();

        $sent = 0;
        foreach ($orders as $order) {
            if (! $order->user) {
                continue;
            }

            // Skip if user already left a review for this order
            if ($order->items->contains(fn ($item) => $item->review()->exists())) {
                continue;
            }

            Mail::to($order->user->email)
                ->queue(new ReviewRequestMail($order->user, $order));

            $sent++;
            $this->line("Review request → {$order->user->email} (order {$order->order_number})");
        }

        $this->info("Review request emails queued for {$sent} orders.");

        return self::SUCCESS;
    }
}
