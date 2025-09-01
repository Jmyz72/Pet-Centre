<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operating_hours', function (Blueprint $table) {
            $table->id();

            $table->foreignId('merchant_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('day_of_week'); // 0=Sun … 6=Sat
            $table->unsignedTinyInteger('block_index'); // order per day

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('block_type', ['open', 'break', 'closed'])->default('open');
            $table->string('label')->nullable(); // e.g., Lunch, Cleaning, Prayer

            $table->timestamps();

            $table->unique(
                ['merchant_profile_id', 'day_of_week', 'block_index'],
                'oh_profile_day_block_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operating_hours');
    }
};
