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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('merchant_id')->constrained('merchant_profiles')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();

            $table->enum('booking_type', ['adoption','service','package']);

            $table->foreignId('customer_pet_id')->nullable()->constrained('customer_pets')->nullOnDelete();
            $table->foreignId('pet_id')->nullable()->constrained('pets')->nullOnDelete();

            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->enum('status', ['pending','confirmed','cancelled','refunded'])->default('pending');
            $table->decimal('price_amount', 10, 2)->default(0);
            $table->string('payment_ref')->nullable();

            $table->string('idempotency_key', 64)->unique();
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['merchant_id','start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
