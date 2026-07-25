<?php

namespace App\Services;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\BankTransferApprovedMail;
use App\Mail\BankTransferRejectedMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\ReferralRewardMail;
use App\Models\BankTransfer;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentLifecycleService
{
    /**
     * Run the full post-payment lifecycle exactly once.
     * Safe to call from callback, webhook, or bank-transfer approval.
     * The idempotency check at the top prevents double stock decrements, emails, etc.
     */
    public function markOrderPaid(Order $order, string $gateway, array $gatewayData, int $amountSmallestUnit): void
    {
        if ($order->payment_status === 'paid') {
            return;
        }

        DB::transaction(function () use ($order, $gateway, $gatewayData, $amountSmallestUnit) {
            $order->update([
                'status'         => 'paid',
                'payment_status' => 'paid',
                'paid_at'        => now(),
                'tracking_code'  => $order->tracking_code ?? Order::generateTrackingCode(),
            ]);

            // Update or create payment record
            Payment::updateOrCreate(
                ['reference' => $order->payment_reference],
                [
                    'order_id'         => $order->id,
                    'gateway'          => $gateway,
                    'amount'           => $amountSmallestUnit > 0 ? $amountSmallestUnit / 100 : $order->total,
                    'currency'         => $gatewayData['currency'] ?? 'NGN',
                    'status'           => 'success',
                    'gateway_response' => $gatewayData,
                    'paid_at'          => now(),
                ]
            );

            // Status history
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => 'paid',
                'note'       => "Payment confirmed via {$gateway}.",
                'changed_by' => null,
            ]);

            // Increment coupon usage only after payment is confirmed
            if ($order->coupon_id) {
                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }

            // Decrement stock — floored at 0 so concurrent payments for the
            // last units can never drive stock negative
            $order->loadMissing('items');
            foreach ($order->items as $item) {
                $qty = (int) $item->quantity;
                if ($item->product_id) {
                    DB::table('products')->where('id', $item->product_id)
                        ->update(['stock_quantity' => DB::raw("GREATEST(CAST(stock_quantity AS SIGNED) - {$qty}, 0)")]);
                }
                if ($item->variant_id) {
                    DB::table('product_variants')->where('id', $item->variant_id)
                        ->update(['stock_quantity' => DB::raw("GREATEST(CAST(stock_quantity AS SIGNED) - {$qty}, 0)")]);
                }
            }

            // Reward referrer on referred user's first paid order
            $this->rewardReferrer($order);
        });

        // Send emails outside transaction so a mail failure never rolls back payment state
        $this->sendPaymentConfirmationEmails($order);
    }

    /**
     * Admin approves a bank transfer — marks the order paid and notifies the customer.
     */
    public function markBankTransferApproved(Order $order, BankTransfer $transfer, int $adminId): void
    {
        $this->markOrderPaid($order->fresh(), 'bank_transfer', [], 0);

        $transfer->update([
            'status'      => 'approved',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($order->user?->email ?? $order->guest_email)
                ->send(new BankTransferApprovedMail($order));
        } catch (\Throwable $e) {
            Log::error('BankTransferApprovedMail failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Admin rejects a bank transfer — cancels the order and notifies the customer.
     */
    public function markBankTransferRejected(Order $order, BankTransfer $transfer, int $adminId, string $note = ''): void
    {
        $transfer->update([
            'status'      => 'rejected',
            'admin_note'  => $note,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        $order->update([
            'status'         => 'cancelled',
            'payment_status' => 'failed',
        ]);

        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => 'cancelled',
            'note'       => 'Bank transfer rejected by admin' . ($note ? ": {$note}" : '.'),
            'changed_by' => $adminId,
        ]);

        try {
            Mail::to($order->user?->email ?? $order->guest_email)
                ->send(new BankTransferRejectedMail($order, $note));
        } catch (\Throwable $e) {
            Log::error('BankTransferRejectedMail failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Reward the referrer on the referred user's first ever paid order.
     * Exact logic copied from PaymentController::rewardReferrer().
     */
    private function rewardReferrer(Order $order): void
    {
        try {
            if (! $order->user_id) {
                return;
            }

            $referral = Referral::where('referred_id', $order->user_id)
                ->where('status', 'pending')
                ->first();

            if (! $referral) {
                return;
            }

            // Only reward on the referred user's first ever paid order
            $paidOrderCount = Order::where('user_id', $order->user_id)
                ->where('payment_status', 'paid')
                ->count();

            if ($paidOrderCount > 1) {
                return;
            }

            $triggerMin = (float) (Setting::get('referral_trigger_min_order') ?: 50000);
            if ($order->total < $triggerMin) {
                return;
            }

            $rewardPercent  = (int) (Setting::get('referral_reward_percent') ?: 10);
            $couponMinOrder = (float) (Setting::get('referral_coupon_min_order') ?: 5000);
            $validityDays   = (int) (Setting::get('referral_coupon_validity_days') ?: 90);

            $code = 'REF-' . strtoupper(substr(base_convert(bin2hex(random_bytes(4)), 16, 36), 0, 6));

            Coupon::create([
                'code'             => $code,
                'type'             => 'percentage',
                'value'            => $rewardPercent,
                'min_order_amount' => $couponMinOrder,
                'max_uses'         => 1,
                'used_count'       => 0,
                'valid_from'       => now(),
                'valid_until'      => now()->addDays($validityDays),
                'is_active'        => true,
            ]);

            $referral->update([
                'order_id'           => $order->id,
                'reward_coupon_code' => $code,
                'status'             => 'rewarded',
            ]);

            try {
                $referrer = $referral->referrer;
                if ($referrer) {
                    Mail::to($referrer->email)->send(
                        new ReferralRewardMail($referrer, $code, $rewardPercent, $validityDays)
                    );
                }
            } catch (\Exception $e) {
                Log::error('Referral reward email failed: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {
            Log::error('Referral reward failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Send order confirmation to customer and notification to admin + sales reps.
     * Exact logic copied from PaymentController::markOrderPaid().
     */
    private function sendPaymentConfirmationEmails(Order $order): void
    {
        // Customer confirmation email
        try {
            $email = $order->user?->email ?? $order->guest_email;
            if ($email) {
                $order->load('items.product');
                Mail::to($email)->send(new OrderConfirmationMail($order));
            }
        } catch (\Exception $e) {
            Log::error('Order confirmation email failed: ' . $e->getMessage());
        }

        // Admin notification (hello@ only)
        try {
            $order->loadMissing('items.product');
            $adminEmail = config('services.admin.email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
            }
        } catch (\Exception $e) {
            Log::error('Admin order notification failed: ' . $e->getMessage());
        }
    }
}
