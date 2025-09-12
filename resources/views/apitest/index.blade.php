@extends('layouts.app')

@push('head')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="container mx-auto p-6">
    @php
        $hasPets  = isset($pets) && is_array($pets) && ($pets['ok'] ?? false) && !empty($pets['data']);
        $hasStaff = isset($staff) && is_array($staff) && ($staff['ok'] ?? false) && !empty($staff['data']);
        $tabDefault = $hasPets ? 'pets' : ($hasStaff ? 'staff' : 'none');
    @endphp

    <div x-data="{ tab: '{{ $tabDefault }}' }">

    <div class="flex flex-col gap-3 mb-6">
        <h1 class="text-2xl font-bold">
            API Test
            <span class="ml-2 align-middle text-sm font-medium text-gray-500">
                @if($hasPets)<span class="mr-2 inline-flex items-center rounded-full bg-pink-50 px-2 py-0.5">🐾 Pets: {{ count($pets['data'] ?? []) }}</span>@endif
                @if($hasStaff)<span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5">👤 Staff: {{ count($staff['data'] ?? []) }}</span>@endif
            </span>
        </h1>

        @if($hasPets && $hasStaff)
            <div class="inline-flex rounded-xl border border-gray-200 bg-white p-1 w-fit">
                <button type="button"
                        @click="tab='pets'"
                        :class="tab==='pets' ? 'bg-pink-100 text-pink-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium">
                    Pets
                </button>
                <button type="button"
                        @click="tab='staff'"
                        :class="tab==='staff' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium">
                    Staff
                </button>
            </div>
        @endif
    </div>

    {{-- Pets Grid --}}
    @if($hasPets)
        <div x-show="tab==='pets'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pets['data'] as $p)
                    @php
                        $rawPath = $p['photo_path'] ?? '';
                        $isHttp  = is_string($rawPath) && (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://'));
                        $imgSrc  = $isHttp ? $rawPath : ( $rawPath ? asset('storage/'.$rawPath) : 'https://placehold.co/600x400?text=Pet');
                        $typeId  = $p['pet_type_id'] ?? ($p['type_id'] ?? '-');
                        $breedId = $p['pet_breed_id'] ?? ($p['breed_id'] ?? '-');
                        $sizeId  = $p['size_id'] ?? '-';
                    @endphp
                    <div class="border rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition">
                        <div class="aspect-video overflow-hidden bg-gray-50">
                            <img src="{{ $imgSrc }}" alt="{{ $p['name'] ?? 'Pet' }}" class="object-cover w-full h-full">
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-lg">{{ $p['name'] ?? 'Unnamed' }}</div>
                                <span class="text-xs rounded-full bg-pink-50 text-pink-700 px-2 py-0.5">#{{ $p['id'] ?? '-' }}</span>
                            </div>
                            <dl class="mt-2 text-sm text-gray-600 grid grid-cols-2 gap-y-1">
                                <div><dt class="inline text-gray-500">Type:</dt> <dd class="inline">{{ $typeId }}</dd></div>
                                <div><dt class="inline text-gray-500">Size:</dt> <dd class="inline">{{ $sizeId }}</dd></div>
                                <div><dt class="inline text-gray-500">Breed:</dt> <dd class="inline">{{ $breedId }}</dd></div>
                            </dl>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Staff Grid --}}
    @if($hasStaff)
        <div x-show="tab==='staff'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($staff['data'] as $s)
                    <div class="border rounded-2xl p-4 bg-white shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="font-semibold text-lg">{{ $s['name'] ?? 'Unnamed' }}</div>
                            <span class="text-xs rounded-full bg-blue-50 text-blue-700 px-2 py-0.5">#{{ $s['id'] ?? '-' }}</span>
                        </div>
                        <dl class="mt-2 text-sm text-gray-600 space-y-1">
                            <div><dt class="inline text-gray-500">Merchant:</dt> <dd class="inline">{{ $s['merchant_id'] ?? '-' }}</dd></div>
                            <div><dt class="inline text-gray-500">Role:</dt> <dd class="inline">{{ $s['role'] ?? '-' }}</dd></div>
                            <div><dt class="inline text-gray-500">Status:</dt> <dd class="inline">{{ $s['status'] ?? '-' }}</dd></div>
                            <div><dt class="inline text-gray-500">Email:</dt> <dd class="inline">{{ $s['email'] ?? '-' }}</dd></div>
                            <div><dt class="inline text-gray-500">Phone:</dt> <dd class="inline">{{ $s['phone'] ?? '-' }}</dd></div>
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(!$hasPets && !$hasStaff)
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
            No data found.
        </div>
    @endif

    {{-- Raw JSON --}}
    <div class="mt-8">
        @php
            $rawPets = $hasPets ? $pets : null;
            $rawStaff = $hasStaff ? $staff : null;
        @endphp
        <div class="flex items-center gap-3 mb-2">
            <h2 class="text-lg font-semibold">Raw JSON</h2>
            @if($hasPets && $hasStaff)
                <span class="text-xs text-gray-500">(reflects selected tab)</span>
            @endif
        </div>
        <template x-if="tab==='pets'">
            <pre class="bg-gray-100 p-3 rounded text-xs overflow-auto">{{ json_encode($rawPets ?? ['ok'=>false,'data'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
        </template>
        <template x-if="tab==='staff'">
            <pre class="bg-gray-100 p-3 rounded text-xs overflow-auto">{{ json_encode($rawStaff ?? ['ok'=>false,'data'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
        </template>
        @if(!$hasPets && !$hasStaff)
            <pre class="bg-gray-100 p-3 rounded text-xs overflow-auto">{{ json_encode(['ok'=>false,'data'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
        @endif

    </div> {{-- end x-data wrapper --}}
    </div>
</div>
@endsection