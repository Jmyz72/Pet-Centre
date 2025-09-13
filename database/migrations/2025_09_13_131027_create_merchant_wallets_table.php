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
        Schema::create('merchant_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchant_profiles')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('pending_balance', 15, 2)->default(0.00); // Money held until release
            $table->string('currency', 3)->default('MYR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('merchant_id');
            $table->index(['merchant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_wallets');
    }
};
