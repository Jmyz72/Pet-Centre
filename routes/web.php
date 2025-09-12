<?php

use App\Http\Controllers\MerchantApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMerchantController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile',[ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/edit', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/become-merchant', [MerchantApplicationController::class, 'becomeMerchant'])->name('merchant.become');
    Route::get('/apply-merchant', [MerchantApplicationController::class, 'chooseType'])->name('merchant.apply');
    Route::post('/apply-merchant/form', [MerchantApplicationController::class, 'showForm'])->name('merchant.apply.form');
    Route::post('/apply-merchant/store', [MerchantApplicationController::class, 'submit'])->name('merchant.apply.submit');
    Route::get('/apply-merchant/submitted', [MerchantApplicationController::class, 'showSubmitted'])->name('merchant.application.submitted');

    /* Chat Route Method */
    Route::get('/chat/{receiverId}', [ChatController::class, 'index'])->name('chat.index');
    Route::delete('/chat/{partnerId}', [ChatController::class, 'deleteChat'])->name('chat.delete');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::put('/chat/message/{id}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/message/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');

    
    /* Merchant Chat Route */
 
    Route::get('/notifications', function () {
        $user = auth()->user();
        return view('notification.index', [
            'unread' => $user->unreadNotifications,
            'all'    => $user->notifications()->latest()->paginate(15),
        ]);
    })->name('notifications.index');

    Route::get('/notifications/{id}/read', function ($id) {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        $data = $notification->data ?? [];
        $link = $data['action_url'] ?? $data['url'] ?? route('notifications.index');

        return redirect($link);
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.readAll');


    
    // Admin merchant application approval/rejection
    Route::post('/admin/merchant-applications/{id}/approve', [MerchantApplicationController::class, 'approve'])
        ->name('admin.merchant-applications.approve');
    Route::post('/admin/merchant-applications/{id}/reject', [MerchantApplicationController::class, 'reject'])
        ->name('admin.merchant-applications.reject');
});

// Public merchant browsing and profile viewing
Route::get('/merchants', [PublicMerchantController::class, 'index'])->name('merchants.index');
Route::get('/merchants/{merchantProfile}', [PublicMerchantController::class, 'show'])->name('merchants.show');

Route::get('/merchants/{merchantProfile}/book', [PublicBookingController::class, 'create'])
    ->name('booking.create');

require __DIR__.'/auth.php';
