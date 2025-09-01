<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merchant_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('role'); // clinic, shelter, groomer
            $table->string('name'); // business/org name
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('license_number')->nullable(); // vet license / grooming cert etc.
            $table->string('document_path')->nullable(); // scanned document upload

            $table->string('status')->default('pending'); // pending, approved, rejected

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchant_applications');
    }
};
