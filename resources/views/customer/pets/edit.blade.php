@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8">
    <h1 class="text-2xl font-semibold">Edit Pet</h1>
    <form method="POST" action="{{ route('customer.pets.update', $pet) }}" enctype="multipart/form-data" class="mt-6 space-y-4">
        @method('PUT')
        @include('customer.pets._form', ['pet' => $pet])
    </form>
</div>
@endsection