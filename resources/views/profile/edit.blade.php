<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-white text-gray-800">

@extends('layouts.app')

@section('content')
    <section class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Update Profile Info --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Profile Information</h2>
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h2>
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Delete Account</h2>
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </section>
@endsection

</body>
</html>