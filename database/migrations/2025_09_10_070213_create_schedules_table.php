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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('merchant_id')->constrained('merchant_profiles')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->enum('block_type', ['booking']);

            $table->timestamps();

            $table->unique(['merchant_id','staff_id','start_at','end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
