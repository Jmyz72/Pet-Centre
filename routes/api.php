<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\ContactController;

// Public API endpoints (no authentication required)
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);

Route::post('/contact', [ContactController::class, 'store']);
Route::get('/contact/messages', [ContactController::class, 'index'])->middleware('auth:sanctum');
Route::get('/contact/messages/{id}', [ContactController::class, 'show'])->middleware('auth:sanctum');
Route::put('/contact/messages/{id}/status', [ContactController::class, 'updateStatus'])->middleware('auth:sanctum');
Route::delete('/contact/messages/{id}', [ContactController::class, 'destroy'])->middleware('auth:sanctum');