<?php

namespace App\Http\Controllers;

use App\Strategies\GroomerStrategy;
use App\Strategies\ServiceContext;

class ServiceController extends Controller
{
    public function showGroomer($merchantId)
    {
        $context = new ServiceContext(new GroomerStrategy());
        $packages = $context->show($merchantId);

        return view('services.groomer.index', [
            'packages' => $packages,
            'type' => 'Groomer'
        ]);
    }
}
    