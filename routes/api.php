<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ContactController;

// Public API endpoints (no authentication required)
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);


// Chat API
Route::middleware('auth:sanctum')->group(function () {
    
    // This route is called by index.blade.php to get messages for a single chat
    Route::get('/chat/conversations/{partnerId}/messages', [ChatController::class, 'index'])->name('api.chat.messages.index');
    // These routes are for sending, updating, and deleting messages
    Route::post('/chat/messages', [ChatController::class, 'store'])->name('api.chat.store')->middleware('throttle:chat');
    Route::put('/chat/messages/{message}', [ChatController::class, 'update'])->name('api.chat.update');
    Route::delete('/chat/messages/{message}', [ChatController::class, 'destroy'])->name('api.chat.destroy');

});

Route::post('/contact', [ContactController::class, 'store']);
Route::get('/contact/messages', [ContactController::class, 'index'])->middleware('auth:sanctum');
Route::get('/contact/messages/{id}', [ContactController::class, 'show'])->middleware('auth:sanctum');
Route::put('/contact/messages/{id}/status', [ContactController::class, 'updateStatus'])->middleware('auth:sanctum');
Route::delete('/contact/messages/{id}', [ContactController::class, 'destroy'])->middleware('auth:sanctum');
