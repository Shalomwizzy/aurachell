<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\StockReservation;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Unicodeveloper\Paystack\Facades\Paystack;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = app(CartService::class)->getOrCreateCart();
        $items = $cart->items()->with(['product.primaryImage', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum(fn ($i) => $i->price_at_add * $i->quantity);
        $user = auth()->user();
        $defaultAddress = $user?->defaultAddress;

        // Default rates for initial render (Lagos as starting zone)
        $shipping = app(ShippingService::class)->getRatesForState(
            $defaultAddress?->state ?? 'Lagos',
            $subtotal
        );

        return view('frontend.checkout', compact('cart', 'items', 'subtotal', 'shipping', 'defaultAddress'));
    }

    public function placeOrder(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name', '')),
            'email' => trim((string) $request->input('email', '')),
            'phone' => trim((string) $request->input('phone', '')),
            'address_line_1' => trim((string) $request->input('address_line_1', '')),
            'address_line_2' => trim((string) $request->input('address_line_2', '')),
            'city' => trim((string) $request->input('city', '')),
            'state' => trim((string) $request->input('state', '')),
            'country' => trim((string) $request->input('country', 'Nigeria')),
            'coupon_code' => trim((string) $request->input('coupon_code', '')),
        ]);

        Log::info('Checkout placeOrder POST', [
            'state' => $request->input('state'),
            'shipping' => $request->input('shipping_method'),
        ]);

        // Use email:filter (filter_var) instead of strict RFC — far more
        // forgiving with real-world emails (gmail/yahoo addresses always pass).
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email:filter|max:150',
            'phone' => 'required|string|max:30',
            'address_line_1' => 'required|string|max:200',
            'address_line_2' => 'nullable|string|max:200',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'nullable|string|max:60',
            'shipping_method' => 'required|in:standard,express',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $cart = app(CartService::class)->getOrCreateCart();
        $items = $cart->items()->with(['product', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        // Stock validation — check both product stock and variant stock
        foreach ($items as $item) {
            $product = $item->product;
            if (! $product || ! $product->is_active) {
                return redirect()->route('cart')->with('error', "Sorry, \"{$product?->name}\" is no longer available.");
            }

            if ($item->variant_id && $item->variant) {
                $variantAvailable = max(0, $item->variant->stock_quantity - StockReservation::reservedQuantity($product->id, $item->variant_id, $cart->id));
                if ($variantAvailable < $item->quantity) {
                    $label = "\"{$product->name}\" ({$item->variant->name})";
                    $msg = $variantAvailable > 0
                        ? "Only {$variantAvailable} unit(s) of {$label} left. Please update your cart."
                        : "{$label} is now out of stock. Please remove it from your cart.";

                    return redirect()->route('cart')->with('error', $msg);
                }
            } else {
                $available = $product->availableStock($cart->id);
                if ($available < $item->quantity) {
                    $msg = $available > 0
                        ? "Only {$available} unit(s) of \"{$product->name}\" left. Please update your cart."
                        : "\"{$product->name}\" is now out of stock. Please remove it from your cart.";

                    return redirect()->route('cart')->with('error', $msg);
                }
            }
        }

        // Calculate totals
        $subtotal = $items->sum(fn ($i) => $i->price_at_add * $i->quantity);

        // Resolve coupon
        $coupon = null;
        $discount = 0;
        if (! empty($data['coupon_code'])) {
            $coupon = Coupon::where('code', strtoupper($data['coupon_code']))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        // Calculate shipping via zone-based rates
        $shippingFee = app(ShippingService::class)->calculate(
            $data['state'] ?? '',
            $subtotal,
            $data['shipping_method']
        );

        $total = max(0, $subtotal - $discount + $shippingFee);

        $shippingAddress = [
            'full_name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address_line_1' => $data['address_line_1'],
            'address_line_2' => $data['address_line_2'] ?? '',
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'] ?? 'Nigeria',
        ];

        // Persist order
        try {
            $order = DB::transaction(function () use ($items, $data, $coupon, $subtotal, $discount, $shippingFee, $total, $shippingAddress) {
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'guest_email' => auth()->guest() ? $data['email'] : null,
                    'guest_phone' => auth()->guest() ? $data['phone'] : null,
                    'guest_name' => auth()->guest() ? $data['name'] : null,
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'tax' => 0,
                    'discount' => $discount,
                    'total' => $total,
                    'currency' => 'NGN',
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'shipping_address' => $shippingAddress,
                    'billing_address' => $shippingAddress,
                    'coupon_id' => $coupon?->id,
                    'coupon_code' => $coupon?->code,
                    'placed_at' => now(),
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'product_name' => $item->product->name,
                        'product_sku' => $item->product->sku,
                        'variant_name' => $item->variant->name ?? null,
                        'scent_note' => $item->scent_note,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->price_at_add,
                        'total_price' => $item->price_at_add * $item->quantity,
                        'product_image' => $item->product->primaryImage?->image_path,
                    ]);
                }

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'note' => 'Order placed. Awaiting payment.',
                    'created_at' => now(),
                ]);

                return $order;
            });
        } catch (\Throwable $e) {
            Log::error('Order creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return back()->with('error', 'We could not save your order. Please try again.');
        }

        // Initialize Paystack payment
        $paymentRef = 'AUR-'.strtoupper(uniqid());
        $order->update(['payment_reference' => $paymentRef]);

        $paystackData = [
            'amount' => (int) round($total * 100), // kobo
            'email' => $data['email'],
            'reference' => $paymentRef,
            'currency' => 'NGN',
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ];

        // The package reads from request input — merge our payload first.
        $request->merge($paystackData);

        try {
            $authUrl = Paystack::getAuthorizationUrl($paystackData)->url;
        } catch (\Throwable $e) {
            Log::error('Paystack init failed', [
                'order' => $order->order_number,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Payment initialization failed. Please try again or contact support.');
        }

        if (empty($authUrl)) {
            Log::error('Paystack returned empty auth URL', ['order' => $order->order_number]);

            return back()->with('error', 'Payment gateway did not respond. Please try again.');
        }

        // Standard Laravel redirect — guaranteed to follow as a 302 to Paystack.
        return redirect()->away($authUrl);
    }

    public function shippingRate(Request $request): JsonResponse
    {
        $state = $request->query('state', '');
        $subtotal = (float) $request->query('subtotal', 0);

        return response()->json(
            app(ShippingService::class)->getRatesForState($state, $subtotal)
        );
    }
}
