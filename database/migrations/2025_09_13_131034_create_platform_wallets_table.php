<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platform_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_type')->default('main'); // main, transaction_fees, platform_fees
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->string('currency', 3)->default('MYR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('wallet_type');
            $table->index(['wallet_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_wallets');
    }
};
