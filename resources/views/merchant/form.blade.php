@extends('layouts.app')

@section('content')
<section class="py-1 bg-white">
    <div class="w-full max-w-xl mx-auto mt-10 mb-8">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Step 1: Select Type</span>
            <span class="text-blue-600 font-semibold">Step 2: Fill Details</span>
            <span>Step 3: Submit</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full w-2/3 transition-all duration-300"></div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-3xl font-bold text-blue-600 mb-8">Apply as {{ ucfirst($merchantType) }}</h1>

        <form method="POST" action="{{ route('merchant.apply.submit') }}" enctype="multipart/form-data" class="text-left max-w-lg mx-auto">
            @csrf
            <input type="hidden" name="role" value="{{ $merchantType }}">

            <!-- Business Name -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Business/Organization Name</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Phone Number</label>
                <input type="text" name="phone" class="w-full border p-2 rounded" required>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Address</label>
                <textarea name="address" class="w-full border p-2 rounded" rows="3"></textarea>
            </div>

            <!-- Business Registration Number -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Business Registration Number</label>
                <input type="text" name="registration_number" class="w-full border p-2 rounded">
            </div>

            <!-- License Number -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">License Number</label>
                <input type="text" name="license_number" class="w-full border p-2 rounded">
            </div>

            <!-- Document Upload -->
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Upload License or Permit (PDF/Image)</label>
                <input type="file" name="document" class="w-full border p-2 rounded" accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</section>

@endsection
