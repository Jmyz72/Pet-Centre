@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Notifications</h1>
        @if(Auth::user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:underline">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    {{-- Unread --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-3">Unread</h2>

        @php
            $user = Auth::user();
            $unread = isset($unread) ? $unread : $user->unreadNotifications;
        @endphp

        @if($unread->count() > 0)
            <div class="space-y-2">
                @foreach($unread as $n)
                    <a href="{{ route('notifications.read', $n->id) }}"
                       class="block border rounded p-3 bg-yellow-50 hover:bg-yellow-100 transition">
                        <p class="font-semibold text-gray-900">
                            {{ $n->data['title'] ?? 'Notification' }}
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            {{ $n->data['message'] ?? '' }}
                        </p>
                        <div class="flex justify-end">
                            <span class="text-xs text-gray-500">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No unread notifications.</p>
        @endif
    </div>

    {{-- All --}}
    <div>
        <h2 class="text-lg font-semibold mb-3">All</h2>

        @php
            $allList = isset($all) ? $all : $user->notifications()->latest()->paginate(15);
        @endphp

        @if($allList->count() > 0)
            <div class="space-y-2">
                @foreach($allList as $n)
                    <a href="{{ route('notifications.read', $n->id) }}"
                       class="block border rounded p-3 hover:bg-gray-50 transition {{ $n->read_at ? '' : 'bg-yellow-50' }}">
                        <p class="font-semibold text-gray-900">
                            {{ $n->data['title'] ?? 'Notification' }}
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            {{ $n->data['message'] ?? '' }}
                        </p>
                        <div class="flex justify-end">
                            <span class="text-xs text-gray-500">
                                {{ $n->created_at->diffForHumans() }}
                                @if(!$n->read_at)
                                    · <span class="text-yellow-700 font-medium">unread</span>
                                @endif
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $allList->links() }}
            </div>
        @else
            <p class="text-gray-500">No notifications yet.</p>
        @endif
    </div>
    </div>
</div>
@endsection