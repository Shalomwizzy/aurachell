<?php

namespace App\Console\Commands;

use App\Mail\CartAbandonmentMail;
use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class SendCartReminder extends Command
{
    protected $signature = 'emails:cart-reminder';

    protected $description = 'Send staged cart abandonment emails (3-stage: 24h, 48h, 72h)';

    public function handle(): int
    {
        $hasColumns = Schema::hasColumn('carts', 'last_reminder_at') &&
                      Schema::hasColumn('carts', 'reminder_count');

        $carts = Cart::with(['user', 'items.product'])
            ->whereHas('user')
            ->whereHas('items')
            ->where('updated_at', '<=', now()->subHours(24))
            ->where('updated_at', '>=', now()->subDays(7))
            ->get();

        $sent = 0;
        foreach ($carts as $cart) {
            if (! $cart->user || $cart->items->isEmpty()) {
                continue;
            }

            $reminderCount = $hasColumns ? (int) $cart->reminder_count : 0;
            $lastReminder  = $hasColumns ? $cart->last_reminder_at : null;

            // Determine which stage to send
            if ($reminderCount === 0) {
                // Stage 1 — 24h after abandonment
                if ($cart->updated_at->lte(now()->subHours(24))) {
                    $stage = 1;
                } else {
                    continue;
                }
            } elseif ($reminderCount === 1) {
                // Stage 2 — 48h+ since abandonment and 24h+ since last reminder
                if ($cart->updated_at->lte(now()->subHours(48)) &&
                    $lastReminder && $lastReminder->lte(now()->subHours(24))) {
                    $stage = 2;
                } else {
                    continue;
                }
            } elseif ($reminderCount === 2) {
                // Stage 3 — 72h+ since abandonment and 24h+ since last reminder
                if ($cart->updated_at->lte(now()->subHours(72)) &&
                    $lastReminder && $lastReminder->lte(now()->subHours(24))) {
                    $stage = 3;
                } else {
                    continue;
                }
            } else {
                // Already sent all 3 stages — stop
                continue;
            }

            Mail::to($cart->user->email)
                ->queue(new CartAbandonmentMail($cart->user, $cart->items->load('product'), $stage));

            if ($hasColumns) {
                $cart->update([
                    'last_reminder_at' => now(),
                    'reminder_count'   => $reminderCount + 1,
                ]);
            }

            $sent++;
            $this->line("Stage {$stage} → {$cart->user->email}");
        }

        $this->info("Cart abandonment emails queued for {$sent} users.");

        return self::SUCCESS;
    }
}
