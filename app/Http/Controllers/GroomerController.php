<?php

namespace App\Http\Controllers;

use App\Models\Package;

class GroomerController extends Controller
{
    public function index()
    {
        // Fetch all active packages with relationships
        $packages = Package::with([
            'merchantProfile',
            'packageTypes',
            'petTypes',
            'petBreeds',
            'packageSizes',
            'variations'
        ])->where('is_active', true)->get();

        // Pass the variable to the view
        return view('services.groomer.index', compact('packages'));
    }

    public function packageSizes()
    {
        return $this->belongsToMany(
            Size::class,
            'package_sizes', // pivot table
            'package_id',
            'size_id'
        );
    }

}
