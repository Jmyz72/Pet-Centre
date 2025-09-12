@extends('layouts.app')

@section('content')
    <section class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">My Profile</h2>

                <div class="space-y-4">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
