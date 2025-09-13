<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing bookings to populate payment_ref from related payments
        DB::statement("
            UPDATE bookings 
            SET payment_ref = (
                SELECT payment_ref 
                FROM payments 
                WHERE payments.booking_id = bookings.id 
                LIMIT 1
            ) 
            WHERE payment_ref IS NULL 
            AND EXISTS (
                SELECT 1 
                FROM payments 
                WHERE payments.booking_id = bookings.id
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally clear payment_ref if needed to rollback
        DB::table('bookings')->update(['payment_ref' => null]);
    }
};