<?php

namespace App\Console\Commands;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReconcilePaystackOrders extends Command
{
    protected $signature = 'paystack:reconcile {--dry-run : Show what would change without saving}';

    protected $description = 'Check pending orders older than 1 hour against the Paystack API and mark paid ones.';

    public function handle(): int
    {
        $dry = $this->option('dry-run');

        $orders = Order::where('payment_status', 'pending')
            ->whereNotNull('payment_reference')
            ->where('created_at', '<=', now()->subHour())
            ->where('created_at', '>=', now()->subDays(3))
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No pending orders to reconcile.');

            return self::SUCCESS;
        }

        $this->info("Checking {$orders->count()} order(s)…");

        $secret = config('paystack.secretKey');
        $baseUrl = rtrim(config('paystack.paymentUrl', 'https://api.paystack.co'), '/');

        $confirmed = 0;
        $failed = 0;

        foreach ($orders as $order) {
            try {
                $response = Http::withToken($secret)
                    ->timeout(10)
                    ->get("{$baseUrl}/transaction/verify/{$order->payment_reference}");

                if (! $response->successful()) {
                    Log::warning("Paystack reconcile: API error for {$order->order_number}", [
                        'status' => $response->status(),
                    ]);

                    continue;
                }

                $data = $response->json('data');
                $status = $data['status'] ?? 'unknown';

                if ($status === 'success') {
                    $this->line("  ✓ {$order->order_number} → paid");

                    if (! $dry) {
                        $order->update([
                            'status' => 'paid',
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'tracking_code' => $order->tracking_code ?? Order::generateTrackingCode(),
                        ]);

                        Payment::updateOrCreate(
                            ['reference' => $order->payment_reference],
                            [
                                'order_id' => $order->id,
                                'gateway' => 'paystack',
                                'amount' => ($data['amount'] ?? 0) / 100,
                                'currency' => $data['currency'] ?? 'NGN',
                                'status' => 'success',
                                'gateway_response' => $data,
                                'paid_at' => now(),
                            ]
                        );

                        OrderStatusHistory::create([
                            'order_id' => $order->id,
                            'status' => 'paid',
                            'note' => 'Payment confirmed by Paystack reconciliation cron.',
                            'changed_by' => null,
                        ]);

                        // Increment coupon usage now that payment is confirmed
                        if ($order->coupon_id) {
                            Coupon::where('id', $order->coupon_id)->increment('used_count');
                        }

                        // Decrement stock
                        foreach ($order->items as $item) {
                            $item->product?->decrement('stock_quantity', $item->quantity);
                            $item->variant?->decrement('stock_quantity', $item->quantity);
                        }

                        // Customer confirmation email
                        try {
                            $email = $order->user?->email ?? $order->guest_email;
                            if ($email) {
                                $order->load('items.product');
                                Mail::to($email)->send(new OrderConfirmationMail($order));
                            }
                        } catch (\Exception $e) {
                            Log::error("Reconcile: confirmation email failed for {$order->order_number}: ".$e->getMessage());
                        }

                        // Admin notification
                        try {
                            $notified = [];
                            $adminEmail = config('services.admin.email');
                            if ($adminEmail) {
                                Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
                                $notified[] = strtolower($adminEmail);
                            }
                            $salesReps = User::role('sales_rep')->get();
                            foreach ($salesReps as $rep) {
                                if (! in_array(strtolower($rep->email), $notified)) {
                                    Mail::to($rep->email)->send(new AdminOrderNotificationMail($order));
                                    $notified[] = strtolower($rep->email);
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error("Reconcile: admin notification failed for {$order->order_number}: ".$e->getMessage());
                        }
                    }

                    $confirmed++;

                } elseif (in_array($status, ['failed', 'abandoned'])) {
                    $this->line("  ✗ {$order->order_number} → {$status}");

                    if (! $dry) {
                        $order->update(['payment_status' => 'failed']);
                    }

                    $failed++;
                } else {
                    $this->line("  ? {$order->order_number} → {$status} (skipped)");
                }
            } catch (\Exception $e) {
                Log::error("Paystack reconcile exception for {$order->order_number}: ".$e->getMessage());
                $this->warn("  ! {$order->order_number} → exception: ".$e->getMessage());
            }
        }

        $suffix = $dry ? ' (dry run — nothing saved)' : '';
        $this->info("Done. Confirmed: {$confirmed}, Failed/abandoned: {$failed}{$suffix}");

        return self::SUCCESS;
    }
}
