<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantPackageController;

Route::get('/v1/merchants/{merchant}/packages', [MerchantPackageController::class, 'index']);
