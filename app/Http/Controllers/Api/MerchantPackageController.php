<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Http\Resources\PackageResourceApi;

class MerchantPackageController extends Controller
{
    public function index(int $merchantId)
    {
        $packages = Package::with([
                'packageTypes:id,name',
                'packageSizes:id,label',
                'petTypes:id,name',
                'petBreeds:id,name',
            ])
            ->where('merchant_id', $merchantId)
            ->where('is_active', true)
            ->get();

        return PackageResourceApi::collection($packages);
    }
}
