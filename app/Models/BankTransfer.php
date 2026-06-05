<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransfer extends Model
{
    protected $fillable = [
        'order_id', 'reference', 'amount', 'currency',
        'bank_name', 'account_number', 'account_name',
        'proof_path', 'proof_original_name',
        'customer_note', 'admin_note', 'status',
        'reviewed_by', 'reviewed_at', 'submitted_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'reviewed_at' => 'datetime',
        'submitted_at'=> 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
