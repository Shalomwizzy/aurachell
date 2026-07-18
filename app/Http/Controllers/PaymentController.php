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
use App\Services\FlutterwaveService;
use App\Services\PaymentLifecycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Unicodeveloper\Paystack\Paystack;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentLifecycleService $lifecycle,
        private FlutterwaveService $flutterwave,
    ) {}

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
            $data       = $paymentDetails['data'];
            $amountKobo = (int) ($data['amount'] ?? 0);

            // Verify the amount actually paid matches the order total
            $expectedKobo = (int) round($order->total * 100);
            if ($amountKobo < $expectedKobo) {
                Log::error('Paystack amount mismatch', [
                    'order' => $order->order_number,
                    'expected_kobo' => $expectedKobo,
                    'paid_kobo' => $amountKobo,
                ]);
                $order->update(['payment_status' => 'failed']);

                return redirect()->route('checkout')
                    ->with('error', 'Payment amount did not match your order total. Please contact support with reference '.$reference.'.');
            }

            $this->lifecycle->markOrderPaid($order, 'paystack', $data, $amountKobo);

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

    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        $secret    = config('paystack.secretKey');

        if (hash_hmac('sha512', $payload, $secret) !== $signature) {
            abort(401, 'Invalid signature');
        }

        $event = json_decode($payload, true);
        Log::info('Paystack webhook', ['event' => $event['event'] ?? 'unknown']);

        if (($event['event'] ?? '') === 'charge.success') {
            $reference  = $event['data']['reference'];
            $order      = Order::where('payment_reference', $reference)->with('items')->first();
            if ($order) {
                $data         = $event['data'];
                $amountKobo   = (int) ($data['amount'] ?? 0);
                $expectedKobo = (int) round($order->total * 100);

                if ($amountKobo < $expectedKobo) {
                    Log::error('Paystack webhook amount mismatch', [
                        'order' => $order->order_number,
                        'expected_kobo' => $expectedKobo,
                        'paid_kobo' => $amountKobo,
                    ]);
                } else {
                    $this->lifecycle->markOrderPaid($order, 'paystack', $data, $amountKobo);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function flutterwaveCallback(Request $request): \Illuminate\Http\RedirectResponse
    {
        $status        = $request->query('status');
        $txRef         = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');

        $order = \App\Models\Order::where('payment_reference', $txRef)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        if ($status !== 'successful') {
            \Illuminate\Support\Facades\Log::warning('Flutterwave callback non-successful', compact('status', 'txRef'));
            return redirect()->route('checkout')->with('error', 'Payment was not completed. Please try again.');
        }

        try {
            $data = app(\App\Services\FlutterwaveService::class)->verifyTransaction($transactionId);

            $expectedKobo = (int) round($order->total * 100);
            $paidKobo     = (int) round(($data['amount'] ?? 0) * 100);

            if ($paidKobo < $expectedKobo) {
                \Illuminate\Support\Facades\Log::error('Flutterwave amount mismatch', compact('expectedKobo', 'paidKobo', 'txRef'));
                return redirect()->route('checkout')->with('error', 'Payment amount mismatch. Please contact support.');
            }

            app(\App\Services\PaymentLifecycleService::class)->markOrderPaid($order, 'flutterwave', $data, $paidKobo);

            // Clear cart
            app(\App\Services\CartService::class)->clearCart();
            session()->put('last_order_number', $order->order_number);

            return redirect()->route('order.success', $order->order_number);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Flutterwave callback error', ['error' => $e->getMessage(), 'txRef' => $txRef]);
            return redirect()->route('checkout')->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function flutterwaveWebhook(Request $request): \Illuminate\Http\JsonResponse
    {
        $signature = $request->header('verif-hash', '');
        $payload   = $request->getContent();

        if (!app(\App\Services\FlutterwaveService::class)->validateWebhookSignature($payload, $signature)) {
            \Illuminate\Support\Facades\Log::warning('Flutterwave webhook signature invalid');
            return response()->json(['status' => 'error'], 401);
        }

        $event = $request->input('event');
        $data  = $request->input('data', []);

        if ($event !== 'charge.completed' || ($data['status'] ?? '') !== 'successful') {
            return response()->json(['status' => 'ignored']);
        }

        $txRef = $data['tx_ref'] ?? null;
        $order = \App\Models\Order::where('payment_reference', $txRef)->first();

        if (!$order) {
            return response()->json(['status' => 'not_found']);
        }

        try {
            $amountKobo = (int) round(($data['amount'] ?? 0) * 100);
            app(\App\Services\PaymentLifecycleService::class)->markOrderPaid($order, 'flutterwave', $data, $amountKobo);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Flutterwave webhook error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(['status' => 'ok']);
    }
}
