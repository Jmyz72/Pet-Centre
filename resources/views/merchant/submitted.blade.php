@extends('layouts.app')

@section('content')
<section class="py-1 bg-white">
    <div class="w-full max-w-xl mx-auto mt-10 mb-8">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Step 1: Select Type</span>
            <span>Step 2: Fill Details</span>
            <span class="text-blue-600 font-semibold">Step 3: Submit</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full w-full transition-all duration-300"></div>
        </div>
    </div>
</section>

<section class="pt-6 pb-1 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        @if($application->status === 'rejected' && $application->rejection_reason)
            <div class="mb-6 p-4 rounded bg-red-100 text-red-800 border border-red-300">
                <strong>Application Rejected:</strong> {{ $application->rejection_reason}}
            </div>
        @endif

        @if($application->status === 'rejected')
            @if($application->can_reapply)
                <div class="mb-6 p-4 rounded bg-green-100 text-green-800 border border-green-300">
                    You are allowed to reapply. Please update your details and submit again.
                    <a href="{{ route('merchant.apply') }}" class="ml-2 text-blue-600 underline hover:text-blue-800">Go to Application Form</a>
                </div>
            @else
                <div class="mb-6 p-4 rounded bg-gray-100 text-gray-800 border border-gray-300">
                    You are not allowed to reapply for this role at the moment.
                </div>
            @endif
        @endif
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-3xl mx-auto bg-gray-50 rounded shadow px-6 py-8">

        

        <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Total Merchant Applied: {{$totalMerchants}}</h2>

        <div class="space-y-4 text-gray-700 text-base">
            <p><strong>Role:</strong> {{ ucfirst($application->role) }}</p>
            <p><strong>Business/Organization Name:</strong> {{ $application->name }}</p>
            <p><strong>Phone:</strong> {{ $application->phone }}</p>
            <p><strong>Address:</strong> {{ $application->address }}</p>
            <p><strong>Business Registration Number:</strong> {{ $application->registration_number }}</p>
            <p><strong>License Number:</strong> {{ $application->license_number }}</p>
            <p><strong>Status:</strong> 
                <span class="inline-block px-3 py-1 rounded bg-yellow-100 text-yellow-800">
                    {{ ucfirst($application->status) }}
                </span>
            </p>
            @if($application->document_path)
                <p><strong>Document:</strong>
                    <a href="{{ asset('storage/' . $application->document_path) }}" target="_blank" class="text-blue-600 underline">
                        View Uploaded Document
                    </a>
                </p>
            @endif
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Return to Dashboard
            </a>
        </div>
    </div>
</section>
@endsection
