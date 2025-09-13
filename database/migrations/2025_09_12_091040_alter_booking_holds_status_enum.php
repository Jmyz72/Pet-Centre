<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // If you're on MySQL and using enum, you'll need doctrine/dbal to 'change()'
        // composer require doctrine/dbal
        Schema::table('booking_holds', function (Blueprint $table) {
            $table->enum('status', ['held', 'converted', 'released', 'expired'])
                  ->default('held')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('booking_holds', function (Blueprint $table) {
            $table->enum('status', ['held', 'released'])
                  ->default('held')
                  ->change();
        });
    }
};