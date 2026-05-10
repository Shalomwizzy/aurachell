<?php

namespace App\Http\Controllers;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\ReferralRewardMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Unicodeveloper\Paystack\Paystack;

class PaymentController extends Controller
{
    public function handleGatewayCallback()
    {
        $paystack = new Paystack;
        $paymentDetails = $paystack->getPaymentData();

        if (! $paymentDetails) {
            return redirect()->route('home')->with('error', 'Payment verification failed.');
        }

        $reference = $paymentDetails['data']['reference'];
        $order = Order::where('payment_reference', $reference)->with('items')->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Idempotency guard — if already paid, go straight to success page
        if ($order->payment_status === 'paid') {
            return redirect()->route('order.success', $order->order_number);
        }

        if ($paymentDetails['data']['status'] === 'success') {
            $this->markOrderPaid($order, $paymentDetails['data']);

            // Clear cart — only possible in callback (session-bound)
            app(CartService::class)->clearCart();

            // Store in session so the guest success page can verify ownership
            session()->put('last_order_number', $order->order_number);

            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'Payment successful! Your order has been confirmed.');
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('checkout')->with('error', 'Payment was not successful. Please try again.');
    }

    /**
     * Run the full post-payment lifecycle exactly once.
     * Safe to call from both the callback and the webhook — the idempotency
     * check at the top prevents double stock decrements, emails, etc.
     */
    private function markOrderPaid(Order $order, array $paystackData): void
    {
        if ($order->payment_status === 'paid') {
            return;
        }

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
                'amount' => ($paystackData['amount'] ?? 0) / 100,
                'currency' => $paystackData['currency'] ?? 'NGN',
                'status' => 'success',
                'gateway_response' => $paystackData,
                'paid_at' => now(),
            ]
        );

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'paid',
            'note' => 'Payment confirmed via Paystack.',
            'changed_by' => null,
        ]);

        // Increment coupon usage only after payment is confirmed
        if ($order->coupon_id) {
            Coupon::where('id', $order->coupon_id)->increment('used_count');
        }

        // Decrement stock
        foreach ($order->items as $item) {
            $item->product?->decrement('stock_quantity', $item->quantity);
            $item->variant?->decrement('stock_quantity', $item->quantity);
        }

        // Reward referrer on referred user's first paid order
        $this->rewardReferrer($order);

        // Customer confirmation email
        try {
            $email = $order->user?->email ?? $order->guest_email;
            if ($email) {
                $order->load('items.product');
                Mail::to($email)->send(new OrderConfirmationMail($order));
            }
        } catch (\Exception $e) {
            Log::error('Order confirmation email failed: '.$e->getMessage());
        }

        // Admin + sales rep notification
        try {
            $order->loadMissing('items.product');
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
            Log::error('Admin order notification failed: '.$e->getMessage());
        }
    }

    private function rewardReferrer(Order $order): void
    {
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

        $rewardPercent = (int) (Setting::get('referral_reward_percent') ?: 10);
        $couponMinOrder = (float) (Setting::get('referral_coupon_min_order') ?: 5000);
        $validityDays = (int) (Setting::get('referral_coupon_validity_days') ?: 90);

        $code = 'REF-'.strtoupper(substr(base_convert(bin2hex(random_bytes(4)), 16, 36), 0, 6));

        Coupon::create([
            'code' => $code,
            'type' => 'percentage',
            'value' => $rewardPercent,
            'min_order_amount' => $couponMinOrder,
            'max_uses' => 1,
            'used_count' => 0,
            'valid_from' => now(),
            'valid_until' => now()->addDays($validityDays),
            'is_active' => true,
        ]);

        $referral->update([
            'order_id' => $order->id,
            'reward_coupon_code' => $code,
            'status' => 'rewarded',
        ]);

        try {
            $referrer = $referral->referrer;
            if ($referrer) {
                Mail::to($referrer->email)->send(
                    new ReferralRewardMail($referrer, $code, $rewardPercent, $validityDays)
                );
            }
        } catch (\Exception $e) {
            Log::error('Referral reward email failed: '.$e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        $secret = config('paystack.secretKey');

        if (hash_hmac('sha512', $payload, $secret) !== $signature) {
            abort(401, 'Invalid signature');
        }

        $event = json_decode($payload, true);
        Log::info('Paystack webhook', ['event' => $event['event'] ?? 'unknown']);

        if (($event['event'] ?? '') === 'charge.success') {
            $reference = $event['data']['reference'];
            $order = Order::where('payment_reference', $reference)->with('items')->first();
            if ($order) {
                $this->markOrderPaid($order, $event['data']);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
