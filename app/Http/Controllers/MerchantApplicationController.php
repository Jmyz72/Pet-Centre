<?php

namespace App\Http\Controllers;

use App\Models\MerchantApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MerchantApplicationController extends Controller
{
    public function chooseType()
    {
        $user = Auth::user();
        $application = \App\Models\MerchantApplication::where('user_id', $user->id)->latest()->first();

        if ($application) {
            if ($application->status === 'pending') {
                return redirect()->route('merchant.application.submitted');
            }
            if ($application->status === 'approved') {
                return redirect()->route('merchant.application.submitted');
            }
            if ($application->status === 'rejected' && ! $application->can_reapply) {
                return redirect()->route('merchant.application.submitted');
            }
        }

        return view('merchant.apply', [
            'selectedType' => $application ? $application->role : null,
        ]); // step 1
    }

    public function showForm(Request $request)
    {
        $allowedRoles = ['clinic', 'shelter', 'groomer'];

        // Role from step 1 (card selection)
        $role = $request->input('merchant_type');

        // Get the user's latest application
        $application = MerchantApplication::where('user_id', Auth::id())
            ->latest()
            ->first();

        // If role missing or invalid, try to fall back to the last application's role
        if (! in_array($role, $allowedRoles)) {
            if ($application && in_array($application->role, $allowedRoles)) {
                $role = $application->role; // fallback for reapply flow
            } else {
                return redirect()->route('merchant.apply')->withErrors('Invalid role selected.');
            }
        }

        // Build prefill only for users allowed to reapply (rejected + can_reapply)
        $prefill = [];
        if ($application && $application->status === 'rejected' && $application->can_reapply) {
            $prefill = [
                'name' => $application->name,
                'phone' => $application->phone,
                'address' => $application->address,
                'registration_number' => $application->registration_number,
                'license_number' => $application->license_number,
                'document_url' => $application->document_path ? Storage::url($application->document_path) : null,
            ];
        }

        return view('merchant.form', [
            'merchantType' => $role, // step 2 (will also auto-select the card via JS if used there)
            'prefill' => $prefill,
        ]);
    }

    public function submit(Request $request)
    {
        // Find latest application to determine if a document already exists
        $latest = MerchantApplication::where('user_id', Auth::id())->latest()->first();
        $hasExisting = $latest && ! empty($latest->document_path);

        $validated = $request->validate([
            'role' => 'required|in:clinic,shelter,groomer',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',
            'license_number' => 'required|string|max:100',
            // Required only if no previous document exists; otherwise optional
            'document' => ($hasExisting ? 'nullable' : 'required').'|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'name.required' => 'Please enter your business or organization name.',
            'phone.required' => 'Phone number is required.',
            'address.required' => 'Business address is required.',
            'registration_number.required' => 'Registration number is required.',
            'license_number.required' => 'License number is required.',
            'document.required' => 'Please upload a valid document.',
        ]);

        // If a new file is uploaded, store it; else keep existing path (if any)
        $documentPath = $hasExisting ? $latest->document_path : null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('merchant_docs', 'public');
        }

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

        if (! $application) {
            return redirect()->route('merchant.apply')->withErrors('No application found.');
        }

        return view('merchant.submitted', compact('application'));
    }

    public function becomeMerchant()
    {
        $user = Auth::user();
        $application = MerchantApplication::where('user_id', $user->id)->latest()->first();

        if ($application) {
            // Always go to submitted page if there's a record
            return redirect()->route('merchant.application.submitted');
        }

        // If no application, go to apply page
        return redirect()->route('merchant.apply');
    }
}
