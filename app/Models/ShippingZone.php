<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'cities', 'is_active', 'sort_order'];

    protected $casts = ['cities' => 'array', 'is_active' => 'boolean'];

    public function rates()
    {
        return $this->hasMany(ShippingRate::class, 'zone_id');
    }

    public function standardRate()
    {
        return $this->hasOne(ShippingRate::class, 'zone_id')->where('method', 'standard');
    }

    public function expressRate()
    {
        return $this->hasOne(ShippingRate::class, 'zone_id')->where('method', 'express');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public static function forCity(string $city): ?self
    {
        return static::active()
            ->get()
            ->first(fn ($z) => in_array(
                strtolower(trim($city)),
                array_map('strtolower', $z->cities ?? [])
            ));
    }
}
