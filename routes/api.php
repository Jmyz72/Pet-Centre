<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\MerchantBookingController;
use App\Http\Controllers\Api\MerchantStaffController;
use App\Http\Controllers\Api\MerchantWalletController;

// Public API endpoints (no authentication required)
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);

// Merchant widget API endpoints
Route::get('/merchants/{merchant}/bookings', [MerchantBookingController::class, 'index']);
Route::get('/merchants/{merchant}/staff', [MerchantStaffController::class, 'index']);
Route::get('/merchants/{merchant}/wallet', [MerchantWalletController::class, 'index']);

// Additional authenticated routes can be added here if needed