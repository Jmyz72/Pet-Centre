<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MerchantBookingRequest;
use App\Http\Resources\MerchantBookingResource;
use App\Models\Booking;

class MerchantBookingController extends Controller
{
    /**
     * GET /api/merchants/{merchant}/bookings
     */
    public function index(MerchantBookingRequest $request, int $merchant)
    {
        $data = $request->validated();

        $q = Booking::query()
            ->where('merchant_id', $merchant)
            ->with(['customer', 'service', 'package', 'customerPet', 'merchantPet', 'staff']);

        if (!empty($data['limit'])) {
            $q->limit($data['limit']);
        }

        if (!empty($data['status'])) {
            $q->where('status', $data['status']);
        }

        if (!empty($data['booking_type'])) {
            $q->where('booking_type', $data['booking_type']);
        }

        if (!empty($data['date_from'])) {
            $q->where('start_at', '>=', $data['date_from']);
        }

        if (!empty($data['date_to'])) {
            $q->where('start_at', '<=', $data['date_to']);
        }

        if (!empty($data['upcoming_only'])) {
            $q->where('start_at', '>=', now());
        }

        $bookings = $q->orderBy('start_at', 'desc')
                     ->select(['id','customer_id','merchant_id','staff_id','service_id','package_id','customer_pet_id','merchant_pet_id','booking_type','status','start_at','end_at','price_amount','payment_ref','created_at'])
                     ->get();

        return response()->json([
            'ok'   => true,
            'data' => MerchantBookingResource::collection($bookings),
        ]);
    }
}