<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\BookingReleaseController;

// Public API endpoints (no authentication required)
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);

// Protected API endpoints (authentication required)
Route::middleware(['auth:sanctum', 'throttle:6,1'])->group(function () {
    // Booking release code management
    Route::post('/bookings/{booking}/release-code', [BookingReleaseController::class, 'generate'])
        ->name('api.bookings.generateReleaseCode');
    Route::post('/bookings/{booking}/release', [BookingReleaseController::class, 'release'])
        ->name('api.bookings.release');
});
