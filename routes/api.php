<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;

// Eligible staff for a merchant's service or package
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);

// Customer pets
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);
