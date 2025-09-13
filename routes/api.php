<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\MerchantDashboardController;
use App\Http\Controllers\Api\MerchantStaffController;
use App\Http\Controllers\Api\MerchantWalletController;
use App\Http\Controllers\Api\MerchantBookingController;

// Public API endpoints (no authentication required)
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);
Route::get('/merchants/{merchant}/staff', [MerchantStaffController::class, 'index']);
Route::get('/merchants/{merchant}/wallet', [MerchantWalletController::class, 'index']);
Route::get('/merchants/{merchant}/bookings', [MerchantBookingController::class, 'index']);

// Authenticated API endpoints for merchants
Route::middleware(['auth'])->prefix('merchant')->group(function () {
    // Dashboard endpoints
    Route::get('/dashboard/overview', [MerchantDashboardController::class, 'overview']);
    Route::get('/bookings/recent', [MerchantDashboardController::class, 'recentBookings']);
    Route::get('/revenue/analytics', [MerchantDashboardController::class, 'revenueAnalytics']);
    Route::get('/wallet/summary', [MerchantDashboardController::class, 'walletSummary']);
    Route::get('/staff/performance', [MerchantDashboardController::class, 'staffPerformance']);
    Route::get('/bookings/stats', [MerchantDashboardController::class, 'bookingStats']);
});
