<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const RESERVE_MINUTES = 30;

    public function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
            // Merge session cart if exists
            $sessionId = Session::getId();
            $sessionCart = Cart::where('session_id', $sessionId)->where('user_id', null)->first();
            if ($sessionCart) {
                foreach ($sessionCart->items as $item) {
                    $this->addToCart($item->product_id, $item->quantity, $item->variant_id, $cart);
                }
                $sessionCart->delete();
            }

            return $cart;
        }

        $sessionId = Session::getId();

        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    public function addToCart(int $productId, int $quantity = 1, ?int $variantId = null, ?Cart $cart = null, ?string $scentNote = null): CartItem
    {
        $cart = $cart ?? $this->getOrCreateCart();
        $product = Product::findOrFail($productId);
        $price = $variantId
            ? ProductVariant::find($variantId)?->price ?? $product->price
            : $product->price;

        $item = $cart->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->where('scent_note', $scentNote)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
            $item->update(['price_at_add' => $price]);
        } else {
            $item = $cart->items()->create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'scent_note' => $scentNote,
                'quantity' => $quantity,
                'price_at_add' => $price,
            ]);
        }

        $this->upsertReservation($cart->id, $productId, $variantId, $item->fresh()->quantity);

        return $item;
    }

    public function removeItem(int $itemId): void
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('id', $itemId)->first();
        if ($item) {
            StockReservation::where('cart_id', $cart->id)
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->delete();
            $item->delete();
        }
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('id', $itemId)->first();
        if (! $item) {
            return;
        }

        if ($quantity <= 0) {
            StockReservation::where('cart_id', $cart->id)
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->delete();
            $item->delete();
        } else {
            $item->update(['quantity' => $quantity]);
            $this->upsertReservation($cart->id, $item->product_id, $item->variant_id, $quantity);
        }
    }

    public function clearCart(?Cart $cart = null): void
    {
        $cart = $cart ?? $this->getOrCreateCart();
        StockReservation::where('cart_id', $cart->id)->delete();
        $cart->items()->delete();
        if (! auth()->check()) {
            $cart->delete();
        }
    }

    public function getItemCount(): int
    {
        try {
            return $this->getOrCreateCart()->items()->sum('quantity');
        } catch (\Exception) {
            return 0;
        }
    }

    private function upsertReservation(int $cartId, int $productId, ?int $variantId, int $quantity): void
    {
        StockReservation::updateOrCreate(
            ['cart_id' => $cartId, 'product_id' => $productId, 'variant_id' => $variantId],
            ['quantity' => $quantity, 'expires_at' => now()->addMinutes(self::RESERVE_MINUTES)]
        );
    }
}
