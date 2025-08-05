<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\MerchantApplication;


class MerchantApplicationController extends Controller
{
    public function chooseType()
    {
        return view('merchant.apply'); // step 1
    }

    public function showForm(Request $request)
    {
        $role = $request->input('merchant_type');

        if (!in_array($role, ['clinic', 'shelter', 'groomer'])) {
            return redirect()->route('merchant.apply')->withErrors('Invalid role selected.');
        }

        return view('merchant.form', ['merchantType' => $role]); // step 2
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:clinic,shelter,groomer',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',
            'license_number' => 'required|string|max:100',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'name.required' => 'Please enter your business or organization name.',
            'phone.required' => 'Phone number is required.',
            'address.required' => 'Business address is required.',
            'registration_number.required' => 'Registration number is required.',
            'license_number.required' => 'License number is required.',
            'document.required' => 'Please upload a valid document.',
        ]);

        // Handle file upload
        $documentPath = $request->file('document')->store('merchant_docs', 'public');

        // Save application to database
        MerchantApplication::create([
            'user_id' => Auth::id(),
            'role' => $validated['role'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'registration_number' => $validated['registration_number'],
            'license_number' => $validated['license_number'],
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('merchant.application.submitted');

    }

    public function showSubmitted()
    {
        $application = \App\Models\MerchantApplication::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$application) {
            return redirect()->route('merchant.apply')->withErrors('No application found.');
        }

        return view('merchant.submitted', compact('application'));
    }

}
