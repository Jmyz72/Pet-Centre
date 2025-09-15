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

    public static function main(): self
    {
        return self::firstOrCreate(['wallet_type' => 'main']);
    }

    public static function transactionFees(): self
    {
        return self::firstOrCreate(['wallet_type' => 'transaction_fees']);
    }

    public static function platformFees(): self
    {
        return self::firstOrCreate(['wallet_type' => 'platform_fees']);
    }
}
