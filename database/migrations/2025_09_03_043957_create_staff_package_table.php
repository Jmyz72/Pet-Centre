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
        Schema::create('staff_package', function (Blueprint $table) {
            $table->foreignId('staff_id')
                  ->constrained('staff')
                  ->cascadeOnDelete();

            $table->foreignId('package_id')
                  ->constrained('packages')
                  ->cascadeOnDelete();

            $table->primary(['staff_id', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_package');
    }
};
