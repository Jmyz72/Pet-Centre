@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    @include('merchant.profile.partials.header', ['profile' => $profile])
    @includeIf('merchant.profile.partials.operating-hours', ['profile' => $profile])
    @includeIf('merchant.profile.roles.' . $profile->role, ['profile' => $profile])

    <div class="mt-8">
        <a href="{{ route('merchants.index') }}" class="inline-block text-blue-600 hover:underline">
            &larr; Back to Merchants
        </a>
    </div>
</div>
@endsection