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
        Schema::create('package_package_type', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_type_id')->constrained()->cascadeOnDelete();
            $table->primary(['package_id', 'package_type_id']); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_package_type');
    }
};
