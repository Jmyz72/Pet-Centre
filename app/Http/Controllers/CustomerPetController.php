<?php

namespace App\Http\Controllers;

use App\Models\CustomerPet;
use App\Models\PetType;
use App\Models\PetBreed;
use App\Http\Requests\StoreCustomerPetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class CustomerPetController extends Controller
{

    /**
     * List current user's pets.
     */
    public function index(Request $request)
    {
        $pets = CustomerPet::with(['type','breed','size'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(12);

        return view('customer.pets.index', compact('pets'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('customer.pets.create', [
            'types'       => PetType::orderBy('name')->pluck('name','id'),
            'breedGroups' => PetBreed::select('id','name','pet_type_id')
                                ->orderBy('name')
                                ->get()
                                ->groupBy('pet_type_id'),
        ]);
    }

    /**
     * Store new pet (owner = current user).
     */
    public function store(StoreCustomerPetRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($file = $request->file('photo')) {
            $data['photo_path'] = $file->store('pets', 'public');
        }

        CustomerPet::create($data);

        return redirect()
            ->route('customer.pets.index')
            ->with('success', 'Pet added.');
    }

    /**
     * Edit form for an existing pet (must belong to user).
     */
    public function edit(CustomerPet $pet)
    {
        $this->ensureOwner($pet);

        return view('customer.pets.edit', [
            'pet'         => $pet->load(['size']),
            'types'       => PetType::orderBy('name')->pluck('name','id'),
            'breedGroups' => PetBreed::select('id','name','pet_type_id')
                                ->orderBy('name')
                                ->get()
                                ->groupBy('pet_type_id'),
        ]);
    }

    /**
     * Update an existing pet.
     */
    public function update(StoreCustomerPetRequest $request, CustomerPet $pet)
    {
        $this->ensureOwner($pet);

        $data = $request->validated();

        if ($file = $request->file('photo')) {
            if ($pet->photo_path) {
                Storage::disk('public')->delete($pet->photo_path);
            }
            $data['photo_path'] = $file->store('pets', 'public');
        }

        $pet->update($data);

        return redirect()
            ->route('customer.pets.index')
            ->with('success', 'Pet updated.');
    }

    /**
     * Delete a pet.
     */
    public function destroy(CustomerPet $pet)
    {
        $this->ensureOwner($pet);

        if ($pet->photo_path) {
            Storage::disk('public')->delete($pet->photo_path);
        }

        $pet->delete();

        return back()->with('success', 'Pet removed.');
    }

    /**
     * Ensure the current user owns the model.
     */
    private function ensureOwner(CustomerPet $pet): void
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to access this pet.');
        }
    }

}