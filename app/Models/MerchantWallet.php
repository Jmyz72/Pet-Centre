<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class MerchantWallet extends Model
{
    protected $fillable = [
        'merchant_id',
        'balance',
        'pending_balance',
        'currency',
        'is_active'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(MerchantProfile::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'wallet', 'wallet_type', 'wallet_id');
    }
    public function addPendingFunds(float $amount, string $description, ?int $bookingId = null): WalletTransaction
    {
        $this->increment('pending_balance', $amount);
        
        return $this->transactions()->create([
            'transaction_id' => Str::uuid(),
            'type' => 'credit',
            'amount' => $amount,
            'currency' => $this->currency ?? 'MYR',
            'description' => $description,
            'booking_id' => $bookingId,
            'status' => 'pending',
            'release_code' => $this->generateReleaseCode(),
        ]);
    }

    public function releaseFunds(string $releaseCode): bool
    {
        $transaction = $this->transactions()
            ->where('release_code', $releaseCode)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            return false;
        }

        $totalAmount = $transaction->amount;
        $transactionFee = $totalAmount * 0.02;
        $platformFee = $totalAmount * 0.10;
        $merchantAmount = $totalAmount - $transactionFee - $platformFee;

        $transaction->update([
            'status' => 'completed',
            'transaction_fee' => $transactionFee,
            'platform_fee' => $platformFee,
            'merchant_amount' => $merchantAmount,
            'released_at' => now(),
        ]);

        $this->decrement('pending_balance', $totalAmount);
        $this->increment('balance', $merchantAmount);

        $this->addPlatformFees($transactionFee, $platformFee, $transaction->booking_id);

        return true;
    }
    private function generateReleaseCode(): string
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (WalletTransaction::where('release_code', $code)->exists());
        
        return $code;
    }
    private function addPlatformFees(float $transactionFee, float $platformFee, ?int $bookingId = null): void
    {
        $transactionWallet = PlatformWallet::firstOrCreate(['wallet_type' => 'transaction_fees']);
        $transactionWallet->increment('balance', $transactionFee);
        $transactionWallet->transactions()->create([
            'transaction_id' => Str::uuid(),
            'type' => 'credit',
            'amount' => $transactionFee,
            'currency' => 'MYR',
            'description' => 'Transaction fee from booking',
            'booking_id' => $bookingId,
            'status' => 'completed',
        ]);

        $platformWallet = PlatformWallet::firstOrCreate(['wallet_type' => 'platform_fees']);
        $platformWallet->increment('balance', $platformFee);
        $platformWallet->transactions()->create([
            'transaction_id' => Str::uuid(),
            'type' => 'credit',
            'amount' => $platformFee,
            'currency' => 'MYR',
            'description' => 'Platform fee from booking',
            'booking_id' => $bookingId,
            'status' => 'completed',
        ]);
    }
}
