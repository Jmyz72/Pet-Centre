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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')
                ->constrained('merchant_profiles')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();

            $table->string('role');   // 'groomer', 'clinic', etc.
            $table->string('status')->default('active');

            $table->timestamps();

            $table->index(['merchant_id','role','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
