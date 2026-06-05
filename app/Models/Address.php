<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'phone', 'email',
        'address_line_1', 'address_line_2', 'city', 'state',
        'country', 'postal_code', 'is_default',
    ];

    // user_id is intentionally excluded from $fillable — it must be set
    // explicitly via relationship (user()->addresses()->create()) or direct
    // assignment, never via mass assignment from request data.
    protected $guarded = ['user_id'];

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
