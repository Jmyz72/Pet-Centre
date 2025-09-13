<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MerchantStaffRequest;
use App\Http\Resources\MerchantStaffResource;
use App\Models\Staff;

class MerchantStaffController extends Controller
{
    /**
     * GET /api/merchants/{merchant}/staff
     */
    public function index(MerchantStaffRequest $request, int $merchant)
    {
        $data = $request->validated();

        $q = Staff::query()
            ->where('merchant_id', $merchant)
            ->where('status', 'active');

        if (!empty($data['limit'])) {
            $q->limit($data['limit']);
        }

        if (!empty($data['with_performance'])) {
            $q->withCount([
                'bookings as bookings_this_month' => function ($query) {
                    $query->whereMonth('start_at', now()->month)
                          ->whereYear('start_at', now()->year)
                          ->where('status', 'completed');
                }
            ]);
        }

        $staff = $q->orderBy('name')
                  ->select(['id','name','email','phone','role','status','merchant_id'])
                  ->get();

        return response()->json([
            'ok'   => true,
            'data' => MerchantStaffResource::collection($staff),
        ]);
    }
}