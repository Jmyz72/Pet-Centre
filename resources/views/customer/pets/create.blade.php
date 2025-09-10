@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8">
    <h1 class="text-2xl font-semibold">Add Pet</h1>
    <form method="POST" action="{{ route('customer.pets.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
        @include('customer.pets._form', ['pet' => new \App\Models\CustomerPet()])
    </form>
</div>
@endsection