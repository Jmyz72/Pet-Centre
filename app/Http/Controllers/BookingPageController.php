<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingPageController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['merchant','service','package','customerPet','merchantPet','staff',])
            ->where('customer_id', auth()->id())
            ->latest('start_at')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    

    public function show(Booking $booking)
    {
        $booking->load(['merchant','service','package','customerPet','merchantPet','staff']);

        return view('bookings.show', compact('booking'));
    }

}