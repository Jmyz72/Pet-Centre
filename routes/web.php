<?php

use App\Http\Controllers\MerchantApplicationController;
use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMerchantController;
use App\Http\Controllers\ContactController; 
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\CustomerPetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ApiTestController; 
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetAdoptionController;
use App\Http\Controllers\ServiceController;
use App\Models\Pet;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/licensing', function () {
    return view('licensing');
})->name('licensing');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/', function () {
    $pets = Pet::query()
        ->adoptable()
        ->with(['petType:id,name', 'petBreed:id,name'])
        ->latest('id')->take(8)->get();

    return view('welcome', compact('pets'));
})->name('welcome');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/adopt', [PetAdoptionController::class, 'index'])->name('pets.index');
Route::get('/adopt/{pet}', [PetAdoptionController::class, 'show'])->name('pets.show');

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');  
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/become-merchant', [MerchantApplicationController::class, 'becomeMerchant'])->name('merchant.become');
    Route::get('/apply-merchant', [MerchantApplicationController::class, 'chooseType'])->name('merchant.apply');
    Route::post('/apply-merchant/form', [MerchantApplicationController::class, 'showForm'])->name('merchant.apply.form');
    Route::post('/apply-merchant/store', [MerchantApplicationController::class, 'submit'])->name('merchant.apply.submit');
    Route::get('/apply-merchant/submitted', [MerchantApplicationController::class, 'showSubmitted'])->name('merchant.application.submitted');

    Route::get('/merchant/profile/roles/{role}', [MerchantApplicationController::class, 'showProfile'])
        ->whereIn('role', ['clinic','groomer','shelter'])
        ->name('merchant.profile.index');

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

        Route::get('/debug-time', function () {
        return now()->toDateTimeString();
    });

    // Customer Pets CRUD
    Route::get('/my-pets',            [CustomerPetController::class,'index'])->name('customer.pets.index');
    Route::get('/my-pets/create',     [CustomerPetController::class,'create'])->name('customer.pets.create');
    Route::post('/my-pets',           [CustomerPetController::class,'store'])->name('customer.pets.store');
    Route::get('/my-pets/{pet}/edit', [CustomerPetController::class,'edit'])->name('customer.pets.edit');
    Route::put('/my-pets/{pet}',      [CustomerPetController::class,'update'])->name('customer.pets.update');
    Route::delete('/my-pets/{pet}',   [CustomerPetController::class,'destroy'])->name('customer.pets.destroy');

    // Booking (web) presentation routes
    Route::get('/bookings/select-pet', [BookingController::class, 'selectPet'])->name('bookings.select-pet');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/available-slots', [BookingController::class, 'availableSlots'])
    ->name('bookings.available-slots');
    Route::get('/bookings/available-staff', [BookingController::class, 'availableStaff'])->name('bookings.available-staff'); // AJAX from the form
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/bank-auth', [BookingController::class, 'bankAuth'])->name('bookings.bank-auth');
    Route::post('/bookings/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');

    Route::get('/bookings', [BookingPageController::class, 'index'])
        ->name('bookings.index');

    Route::get('/bookings/{booking}', [BookingPageController::class, 'show'])
    ->name('bookings.show');

    Route::get('/bookings/quote-price', [BookingController::class, 'quotePrice'])
    ->name('bookings.quote-price');

    Route::get('/payments/{payment}/redirect', [\App\Http\Controllers\PaymentController::class, 'redirect'])
        ->name('payments.redirect');

    Route::get('/payments/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])
        ->name('payments.callback');

    Route::post('/payments/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])
        ->name('payments.webhook');

    Route::get('/apitest', [ApiTestController::class, 'index'])->name('apitest.index');

});

// Public merchant browsing and profile viewing
Route::get('/merchants', [PublicMerchantController::class, 'index'])->name('merchants.index');
Route::get('/merchants/{merchantProfile}', [PublicMerchantController::class, 'show'])->name('merchants.show');
Route::get('/merchants/{merchantProfile}/book', [PublicBookingController::class, 'create'])
    ->name('booking.create');

Route::get('/merchant/profile/roles/{role}', [MerchantApplicationController::class, 'showProfile'])
    ->whereIn('role', ['clinic','groomer','shelter'])
    ->name('merchant.profile.index');

require __DIR__.'/auth.php';

