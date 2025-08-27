<div class="bg-white shadow p-6 rounded-lg flex items-center space-x-6">
    <div class="flex-shrink-0">
        <img class="h-24 w-24 rounded-full object-cover"
            src="{{ $profile->photo ? asset('storage/'.$profile->photo) : asset('images/placeholder-profile.png') }}"
            alt="{{ $profile->name ?? 'Merchant Photo' }}">
    </div>
    <div>
        <h2 class="text-xl font-semibold text-gray-900">{{ $profile->role ?? 'Merchant' }}</h2>
        <h1 class="text-3xl font-bold text-gray-900">{{ $profile->name ?? 'Unnamed Merchant' }}</h1>
        <p class="mt-2 text-gray-600">{{ $profile->description ?? 'No description available.' }}</p>
        <div class="mt-4 space-y-1 text-gray-700">
            @if($profile->phone)
                <p><span class="font-semibold">Phone:</span> {{ $profile->phone }}</p>
            @endif
            @if($profile->address)
                <p><span class="font-semibold">Address:</span> {{ $profile->address }}</p>
            @endif
            @if($profile->registration_number)
                <p><span class="font-semibold">Registration Number:</span> {{ $profile->registration_number }}</p>
            @endif
            @if($profile->license_number)
                <p><span class="font-semibold">License Number:</span> {{ $profile->license_number }}</p>
            @endif
        </div>
    </div>
</div>
