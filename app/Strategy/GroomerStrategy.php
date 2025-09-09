<?php

namespace App\Http\Controllers;

use App\Models\Package;

class GroomerController extends Controller
{
    public function index()
    {
        // Get all active packages with relationships
        $packages = Package::with(['merchantProfile', 'packageTypes', 'petTypes', 'petBreeds', 'packageSizes', 'variations'])
                            ->where('is_active', true)
                            ->get();

        return view('services.groomer.index', compact('packages'));
    }
}
