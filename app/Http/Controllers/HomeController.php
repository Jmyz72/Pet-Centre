<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Service;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function welcome()
    {
        // Load pets for the homepage showcase (change take(4) if you want more)
        $pets = Pet::query()
            ->with(['type:id,name', 'breed:id,name'])
            // ->where('status', 'available')   // uncomment if you have a 'status' column
            ->latest('id')
            ->take(4)
            ->get();

        // Load a few services (optional 'is_active' filter if the column exists)
        $services = Service::query()
            ->when(Schema::hasColumn('services', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->latest('id')
            ->take(4)
            ->get();

        return view('welcome', compact('pets', 'services'));
    }
}
