<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['zone_id', 'method', 'price', 'min_days', 'max_days'];

    protected $casts = ['price' => 'decimal:2'];

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id');
    }

    public function deliveryLabel(): string
    {
        if ($this->min_days === $this->max_days) {
            return "{$this->min_days} day".($this->min_days > 1 ? 's' : '');
        }

        return "{$this->min_days}–{$this->max_days} days";
    }
}
