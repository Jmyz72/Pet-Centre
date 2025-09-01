<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_variations', function (Blueprint $table) {
            $table->id();

            // Parent package
            $table->foreignId('package_id')
                  ->constrained('packages')
                  ->cascadeOnDelete();

            // Link to your pivot rows (now each has its own id)
            $table->foreignId('package_pet_type_id')
                  ->constrained('package_pet_types')
                  ->cascadeOnDelete();

            $table->foreignId('package_size_id')
                  ->nullable()
                  ->constrained('package_sizes')
                  ->cascadeOnDelete();

            // Optional breed override: nullable
            $table->foreignId('package_breed_id')
                  ->nullable()
                  ->constrained('package_breeds')
                  ->cascadeOnDelete();

            // The variation price (overrides package base price when shown)
            $table->decimal('price', 10, 2);

            // Nice-to-have fields (future-proofing)
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unsignedBigInteger('size_key')->storedAs('COALESCE(package_size_id, 0)');
            $table->unsignedBigInteger('breed_key')->storedAs('COALESCE(package_breed_id, 0)');

            $table->unique(['package_id', 'package_pet_type_id', 'size_key', 'breed_key'], 'pv_unique_combo');

            $table->index(['package_id', 'package_pet_type_id']);
            $table->index(['package_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_variations');
    }
};
