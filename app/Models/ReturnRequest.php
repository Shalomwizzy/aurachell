<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    protected $fillable = ['order_id', 'user_id', 'reason', 'details', 'status', 'admin_notes'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'refunded' => 'Refunded',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'approved' => 'text-green-600 bg-green-50',
            'refunded' => 'text-blue-600 bg-blue-50',
            'rejected' => 'text-red-600 bg-red-50',
            default => 'text-amber-600 bg-amber-50',
        };
    }
}
