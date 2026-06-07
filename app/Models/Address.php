<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // user_id is intentionally absent from $fillable.
    // Always create addresses via auth()->user()->addresses()->create()
    // so user_id is set by the relationship, not by request data.
    protected $fillable = [
        'full_name', 'phone', 'email',
        'address_line_1', 'address_line_2', 'city', 'state',
        'country', 'postal_code', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArray(): array
    {
        return parent::toArray();
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->country,
        ]);

        return implode(', ', $parts);
    }
}
