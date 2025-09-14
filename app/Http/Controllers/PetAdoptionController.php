<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetAdoptionController extends Controller
{
    // GET /adopt
    public function index()
{
    $pets = Pet::query()
        ->with(['petType:id,name','petBreed:id,name'])
        ->where('status', 'available')   
        ->latest('id')
        ->paginate(12)
        ->withQueryString();

    return view('pet.index', compact('pets'));
}


    // GET /adopt/{pet}
    public function show(Pet $pet)
    {
        // If you have a status column, block non-available pets
        if (isset($pet->status) && $pet->status !== 'available') {
            abort(404);
        }

        $pet->load(['petType:id,name','petBreed:id,name']);

        return view('pet.show', compact('pet'));
    }
}
