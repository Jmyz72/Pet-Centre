<?php

namespace App\Http\Controllers;

use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PublicBookingController extends Controller
{
    /**
     * Temporary placeholder for grooming booking.
     * We aren't implementing booking yet, so just send the user
     * back to the merchant profile with a friendly flash message.
     */
    public function create(Request $request, MerchantProfile $merchantProfile): RedirectResponse
    {
        // Optional: keep the selected package id for future use.
        $packageId = $request->integer('package');

        // TODO: when booking is ready, validate that the package belongs to this merchant:
        // $merchantProfile->packages()->whereKey($packageId)->firstOrFail();

        return redirect()
            ->route('merchants.show', $merchantProfile)
            ->with('info', 'Booking is coming soon. Please contact the merchant directly.');
    }
}