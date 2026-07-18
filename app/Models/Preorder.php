<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'customer_name', 'customer_email',
        'customer_phone', 'quantity', 'note', 'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
