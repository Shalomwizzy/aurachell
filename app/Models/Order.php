<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'tracking_code', 'user_id',
        'guest_email', 'guest_phone', 'guest_name',
        'subtotal', 'shipping_fee', 'tax', 'discount', 'total', 'currency',
        'status', 'payment_status', 'payment_method', 'payment_reference',
        'shipping_address', 'billing_address', 'coupon_id', 'coupon_code',
        'tracking_number', 'courier', 'notes',
        'placed_at', 'paid_at', 'shipped_at', 'delivered_at',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest('created_at');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public function bankTransfer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BankTransfer::class);
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Guest';
    }

    public function getCustomerEmailAttribute(): string
    {
        return $this->user?->email ?? $this->guest_email ?? '';
    }

    public function getCustomerPhoneAttribute(): string
    {
        return $this->user?->phone
            ?? $this->guest_phone
            ?? ($this->shipping_address['phone'] ?? '');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'paid', 'processing', 'pending_bank_confirmation']);
    }

    public function isPendingBankConfirmation(): bool
    {
        return $this->status === 'pending_bank_confirmation';
    }

    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        $number = 'AUR-'.$year.'-'.str_pad($count, 5, '0', STR_PAD_LEFT);
        while (static::where('order_number', $number)->exists()) {
            $number = 'AUR-'.$year.'-'.str_pad(++$count, 5, '0', STR_PAD_LEFT);
        }

        return $number;
    }

    public static function generateTrackingCode(): string
    {
        do {
            $suffix = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
            $code = 'AUR-'.$suffix;
        } while (static::where('tracking_code', $code)->exists());

        return $code;
    }
}
