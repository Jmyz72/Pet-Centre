<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\BookingReleaseController;

// Eligible staff for a merchant's service or package
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);

// Customer pets
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);

// Booking payout release via one-time code (JSON endpoints)
Route::middleware(['auth','throttle:6,1'])->prefix('api')->group(function () {
    Route::post('/bookings/{booking}/release-code', [BookingReleaseController::class, 'generate'])
        ->name('api.bookings.generateReleaseCode');
    Route::post('/bookings/{booking}/release', [BookingReleaseController::class, 'release'])
        ->name('api.bookings.release');
});
