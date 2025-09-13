<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add 'completed' to the ENUM values
        DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending','confirmed','cancelled','refunded','completed') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Rollback to original enum without 'completed'
        DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending','confirmed','cancelled','refunded') NOT NULL DEFAULT 'pending'");
    }
};