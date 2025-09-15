<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PetCentre') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navigation Bar --}}
    @include('layouts.navigation')

    {{-- Optional Page Header --}}
    @isset($header)
        <header class="bg-white shadow dark:bg-gray-800">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Main Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')


    @auth {{-- Only run this script if the user is logged in --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Find the chat link and create a placeholder for the badge
        const chatLink = $('a[href="{{ route('chat.index') }}"]');
        // We give the badge a specific ID to make it easy to find
        const badgeId = 'chat-notification-badge';

        function updateUnreadBadge(count) {
            // Remove the old badge first
            $('#' + badgeId).remove();

            if (count > 0) {
                // If there are unread messages, create and append the new badge
                const badgeHtml = `
                    <span id="${badgeId}" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                        ${count}
                    </span>
                `;
                chatLink.append(badgeHtml);
            }
        }

        function checkUnreadMessages() {
            $.ajax({
                url: "{{ route('api.chat.unread-count') }}",
                type: "GET",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.unread_count !== undefined) {
                        updateUnreadBadge(response.unread_count);
                    }
                },
                error: function(xhr) {
                    console.error("Could not check for unread messages.");
                }
            });
        }

        // Check for messages every 20 seconds (20000 milliseconds)
        setInterval(checkUnreadMessages, 20000);
    });
    </script>
    @endauth
</body>
</html>
