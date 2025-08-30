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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchant_profiles')->cascadeOnDelete();
            $table->foreignId('pet_type_id')->nullable()->constrained('pet_types');
            $table->foreignId('pet_breed_id')->nullable()->constrained('pet_breeds')->nullOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->string('name');
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->enum('sex', ['male','female','unknown'])->default('unknown');
            $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->boolean('vaccinated')->default(false);
            $table->enum('status', ['draft','available','reserved','adopted','inactive'])->default('draft')->index();
            $table->decimal('adoption_fee', 8, 2)->nullable();
            $table->dateTime('adopted_at')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
