<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'customer_name', 'customer_email',
        'product_name', 'description', 'scent_preference',
        'budget', 'image_path', 'status', 'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? asset('images/product-requests/'.basename($this->image_path))
            : null;
    }
}
