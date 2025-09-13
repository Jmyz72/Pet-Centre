<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MerchantWalletRequest;
use App\Http\Resources\MerchantWalletResource;
use App\Models\MerchantWallet;

class MerchantWalletController extends Controller
{
    /**
     * GET /api/merchants/{merchant}/wallet
     */
    public function index(MerchantWalletRequest $request, int $merchant)
    {
        $data = $request->validated();

        $q = MerchantWallet::query()
            ->where('merchant_id', $merchant);

        if (!empty($data['with_transactions'])) {
            $q->with(['transactions' => function($query) use ($data) {
                $query->orderBy('created_at', 'desc');
                if (!empty($data['transactions_limit'])) {
                    $query->limit($data['transactions_limit']);
                }
            }]);
        }

        $wallet = $q->select(['id','merchant_id','balance','pending_balance','currency','is_active','updated_at'])
                   ->first();

        if (!$wallet) {
            return response()->json([
                'ok'   => false,
                'message' => 'Wallet not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'ok'   => true,
            'data' => new MerchantWalletResource($wallet),
        ]);
    }
}