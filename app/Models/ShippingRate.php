<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['zone_id', 'method', 'price', 'free_shipping_threshold', 'min_days', 'max_days'];

    protected $casts = ['price' => 'decimal:2', 'free_shipping_threshold' => 'decimal:2'];

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id');
    }

    public function isFree(float $subtotal): bool
    {
        return $this->free_shipping_threshold > 0 && $subtotal >= $this->free_shipping_threshold;
    }

    public function effectivePrice(float $subtotal): float
    {
        return $this->isFree($subtotal) ? 0.0 : (float) $this->price;
    }

    public function deliveryLabel(): string
    {
        if ($this->min_days === $this->max_days) {
            return "{$this->min_days} day".($this->min_days > 1 ? 's' : '');
        }

        return "{$this->min_days}–{$this->max_days} days";
    }
}
