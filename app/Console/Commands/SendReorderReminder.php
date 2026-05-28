<?php

namespace App\Console\Commands;

use App\Mail\ReorderReminderMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReorderReminder extends Command
{
    protected $signature = 'emails:reorder-reminder {--days=60 : Days since last order to trigger reminder}';

    protected $description = 'Send reorder reminder emails to customers who haven\'t ordered in N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        // Find customers whose last delivered order was exactly N days ago (within a 1-day window)
        $orders = Order::with(['user', 'items.product'])
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [
                now()->subDays($days + 1),
                now()->subDays($days),
            ])
            ->whereHas('user')
            ->get()
            ->groupBy('user_id')
            ->map(fn ($orders) => $orders->sortByDesc('delivered_at')->first());

        $sent = 0;
        foreach ($orders as $order) {
            if (! $order->user) {
                continue;
            }

            // Skip if user has placed a more recent order
            $hasRecentOrder = Order::where('user_id', $order->user_id)
                ->where('status', 'delivered')
                ->where('delivered_at', '>', $order->delivered_at)
                ->exists();

            if ($hasRecentOrder) {
                continue;
            }

            Mail::to($order->user->email)
                ->queue(new ReorderReminderMail($order->user, $order));

            $sent++;
            $this->line("Reorder reminder → {$order->user->email}");
        }

        $this->info("Reorder reminder emails queued for {$sent} users.");

        return self::SUCCESS;
    }
}
