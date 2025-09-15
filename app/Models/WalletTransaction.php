<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'wallet_type',
        'wallet_id',
        'type',
        'amount',
        'currency',
        'description',
        'booking_id',
        'transaction_fee',
        'platform_fee',
        'merchant_amount',
        'status',
        'release_code',
        'released_at',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_fee' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'merchant_amount' => 'decimal:2',
        'released_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function wallet(): MorphTo
    {
        return $this->morphTo('wallet', 'wallet_type', 'wallet_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getFormattedAmountAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }
}
