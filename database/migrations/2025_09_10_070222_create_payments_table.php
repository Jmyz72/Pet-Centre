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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();

            $table->string('payment_ref')->index(); // gateway intent/charge id or "cod-xxx"
            $table->decimal('amount', 10, 2);
            $table->string('currency', 8)->default('MYR');

            $table->enum('status', ['succeeded','failed','void']);
            $table->string('provider', 32)->default('cod'); // stripe/fpx/cod/etc

            $table->string('idempotency_key', 64)->unique();
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
