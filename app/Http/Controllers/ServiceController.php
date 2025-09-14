<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::query()
            ->when(\Schema::hasColumn('services','is_active'), fn($q) => $q->where('is_active', true))
            ->latest('id')->paginate(12);

        return view('service.index', compact('services'));
    }

    public function show(Service $service)
    {
        return view('service.show', compact('service'));
    }
}
