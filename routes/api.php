<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\ChatController;

// Eligible staff for a merchant's service or package
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);

// Customer pets
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

