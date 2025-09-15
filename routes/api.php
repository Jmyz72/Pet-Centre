<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\CustomerPetController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MerchantBookingController;
use App\Http\Controllers\Api\MerchantStaffController;
use App\Http\Controllers\Api\MerchantWalletController;

// Public API endpoints (no auth required)
Route::get('/merchants/{merchant}/eligible-staff', [StaffController::class, 'index']);
Route::get('/customers/{customer}/pets', [CustomerPetController::class, 'index']);


// Chat API (authenticated)
Route::middleware('auth:sanctum')->group(function () {
    
    // Fetch messages for a single conversation (used by the chat UI)
    Route::get('/chat/conversations/{partnerId}/messages', [ChatController::class, 'index'])->name('api.chat.messages.index');
    // Send, edit, and delete messages
    Route::post('/chat/messages', [ChatController::class, 'store'])->name('api.chat.store')->middleware('throttle:chat');
    Route::put('/chat/messages/{message}', [ChatController::class, 'update'])->name('api.chat.update');
    Route::delete('/chat/messages/{message}', [ChatController::class, 'destroy'])->name('api.chat.destroy');
    Route::get('/chat/unread-count', [App\Http\Controllers\Api\ChatController::class, 'getUnreadCount'])->name('api.chat.unread-count');

});

// Merchant widget API endpoints
Route::get('/merchants/{merchant}/bookings', [MerchantBookingController::class, 'index']);
Route::get('/merchants/{merchant}/staff', [MerchantStaffController::class, 'index']);
Route::get('/merchants/{merchant}/wallet', [MerchantWalletController::class, 'index']);

// Add more authenticated routes here as needed
