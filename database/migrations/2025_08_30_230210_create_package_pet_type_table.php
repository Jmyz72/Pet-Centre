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
        Schema::create('package_pet_type', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pet_type_id')->constrained('pet_types')->cascadeOnDelete();
            $table->primary(['package_id','pet_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_pet_type');
    }
};
