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
        Schema::create('pet_breeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_type_id')->constrained('pet_types')->cascadeOnDelete();
            $table->string('name'); // e.g. Golden Retriever, Persian
            $table->timestamps();

            $table->unique(['pet_type_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_breeds');
    }
};
