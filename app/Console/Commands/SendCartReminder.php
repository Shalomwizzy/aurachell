<?php

namespace App\Console\Commands;

use App\Mail\CartAbandonmentMail;
use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCartReminder extends Command
{
    protected $signature = 'emails:cart-reminder';

    protected $description = 'Send cart abandonment emails to users with items abandoned for 24+ hours';

    public function handle(): int
    {
        $carts = Cart::with(['user', 'items.product'])
            ->whereHas('user')
            ->where('updated_at', '<=', now()->subHours(24))
            ->where('updated_at', '>=', now()->subDays(7))
            ->get();

        $sent = 0;
        foreach ($carts as $cart) {
            if (! $cart->user || $cart->items->isEmpty()) {
                continue;
            }

            Mail::to($cart->user->email)
                ->queue(new CartAbandonmentMail($cart->user, $cart->items));
            $sent++;
        }

        $this->info("Cart abandonment email queued for {$sent} users.");

        return self::SUCCESS;
    }
}
