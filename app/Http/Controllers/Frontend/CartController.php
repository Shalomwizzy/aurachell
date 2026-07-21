<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = app(CartService::class)->getOrCreateCart();
        $items = $cart->items()->with(['product.primaryImage', 'variant', 'product.category'])->get();

        $subtotal = $items->sum(fn ($i) => $i->price_at_add * $i->quantity);
        // Cart shows a default/standard estimate — exact rate is matched by city at checkout
        $defaultRates = app(ShippingService::class)->getRatesForCity('', $subtotal);
        $shippingStandard = $defaultRates['standard']['price'];
        $shippingFee = $defaultRates['standard']['price'];

        $itemsData = $items->map(fn ($item) => [
            'id' => $item->id,
            'quantity' => $item->quantity,
            'price_at_add' => $item->price_at_add,
            'product' => [
                'name' => $item->product->name,
                'slug' => $item->product->slug,
                'category' => $item->product->category?->name ?? '',
                'image' => $item->product->primaryImage ? basename($item->product->primaryImage->image_path) : null,
            ],
            'variant' => $item->variant ? ['name' => $item->variant->name] : null,
        ])->values()->toArray();

        return view('frontend.cart', compact('cart', 'items', 'itemsData', 'subtotal', 'shippingFee', 'shippingStandard'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        app(CartService::class)->addToCart(
            $data['product_id'],
            $data['quantity'] ?? 1,
            $data['variant_id'] ?? null
        );

        $count = app(CartService::class)->getItemCount();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Added to cart', 'count' => $count]);
        }

        return back()->with('success', 'Item added to your cart.');
    }

    public function data()
    {
        $cart = app(CartService::class)->getOrCreateCart();
        $items = $cart->items()->with(['product.primaryImage', 'variant'])->get();
        $subtotal = $items->sum(fn ($i) => $i->price_at_add * $i->quantity);

        return response()->json([
            'items' => $items->map(fn ($item) => [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'price_at_add' => $item->price_at_add,
                'product' => [
                    'name' => $item->product->name,
                    'slug' => $item->product->slug,
                    'image' => $item->product->primaryImage ? basename($item->product->primaryImage->image_path) : null,
                ],
                'variant' => $item->variant ? ['name' => $item->variant->name] : null,
                'scent_note' => $item->scent_note,
            ])->values(),
            'subtotal' => $subtotal,
            'count' => $items->sum('quantity'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        app(CartService::class)->updateQuantity($data['item_id'], $data['quantity']);

        return $this->data();
    }

    public function remove(Request $request)
    {
        $request->validate(['item_id' => 'required|integer']);
        app(CartService::class)->removeItem($request->item_id);

        return $this->data();
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string|max:50']);

        $cart = app(CartService::class)->getOrCreateCart();
        $subtotal = $cart->items()->get()->sum(fn ($i) => $i->price_at_add * $i->quantity);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied! You save ₦'.number_format($discount, 0),
            'discount' => $discount,
            'code' => $coupon->code,
        ]);
    }
}
