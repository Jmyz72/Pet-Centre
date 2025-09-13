<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PlatformWallet extends Model
{
    protected $fillable = [
        'wallet_type',
        'balance',
        'currency',
        'is_active'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'wallet', 'wallet_type', 'wallet_id');
    }

    /**
     * Get the main platform wallet
     */
    public static function main(): self
    {
        return self::firstOrCreate(['wallet_type' => 'main']);
    }

    /**
     * Get the transaction fees wallet
     */
    public static function transactionFees(): self
    {
        return self::firstOrCreate(['wallet_type' => 'transaction_fees']);
    }

    /**
     * Get the platform fees wallet
     */
    public static function platformFees(): self
    {
        return self::firstOrCreate(['wallet_type' => 'platform_fees']);
    }
}
