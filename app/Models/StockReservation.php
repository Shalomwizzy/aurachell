<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    protected $fillable = ['product_id', 'variant_id', 'cart_id', 'quantity', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public static function reservedQuantity(int $productId, ?int $variantId = null, ?int $excludeCartId = null): int
    {
        return (int) static::active()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->when($excludeCartId, fn ($q) => $q->where('cart_id', '!=', $excludeCartId))
            ->sum('quantity');
    }
}
