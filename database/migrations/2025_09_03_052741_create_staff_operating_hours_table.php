<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_operating_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')
                  ->constrained('staff')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('day_of_week'); // 0=Sun … 6=Sat
            $table->unsignedTinyInteger('block_index'); // multiple slots per day

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('block_type', ['open','break','closed'])->default('open');
            $table->string('label')->nullable();

            $table->timestamps();

            $table->unique(['staff_id','day_of_week','block_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_operating_hours');
    }
};