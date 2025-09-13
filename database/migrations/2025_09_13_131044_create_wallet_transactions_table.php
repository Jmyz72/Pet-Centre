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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // UUID for tracking
            
            // Wallet references (polymorphic)
            $table->string('wallet_type'); // 'merchant' or 'platform'
            $table->unsignedBigInteger('wallet_id');
            
            // Transaction details
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('MYR');
            $table->string('description');
            
            // Related booking
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            
            // Fee breakdown (when applicable)
            $table->decimal('transaction_fee', 15, 2)->nullable(); // 2%
            $table->decimal('platform_fee', 15, 2)->nullable();    // 10%
            $table->decimal('merchant_amount', 15, 2)->nullable(); // Net amount to merchant
            
            // Status and metadata
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('release_code', 6)->nullable(); // 6-digit release code
            $table->timestamp('released_at')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['wallet_type', 'wallet_id']);
            $table->index(['booking_id']);
            $table->index(['release_code']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
