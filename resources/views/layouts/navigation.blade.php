<nav class="bg-white border-b border-gray-200 px-4 py-3 dark:bg-gray-800">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
        <a href="/" class="flex items-center space-x-2 rtl:space-x-reverse">
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">PetCentre</span>
        </a>

        {{-- Right Side (Guest or Auth) --}}
        <div class="flex items-center lg:order-2 space-x-2 rtl:space-x-reverse">
            {{-- If not logged in --}}
            @guest
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Login</a>
                <a href="{{ route('register') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">Register</a>
            @endguest

            {{-- If logged in --}}
            @auth
                @php
                    $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
                @endphp
                <div class="flex items-center space-x-4">
                    {{-- --- NEW CHAT BUTTON --- --}}
                    <a href="{{ route('chat.index') }}" class="relative p-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg focus:outline-none" aria-label="Open Chats">
                        <!-- Chat Icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        {{-- --- THIS IS THE NEW INDICATOR --- --}}
                        @if(isset($unreadMessageCount) && $unreadMessageCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                                {{ $unreadMessageCount }}
                            </span>
                        @endif
                        {{-- --------------------------------- --}}
                    </a>
                    {{-- ----------------------- --}}


                    {{-- Notification Dropdown --}}
                    <div class="relative">
                        <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" class="relative p-2 focus:outline-none" type="button">
                            <!-- Bell Icon -->
                            <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <!-- Dropdown Panel -->
                        <div id="dropdownNotification" class="hidden absolute right-0 z-50 mt-2 w-64 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">
                            <div class="p-4 text-sm text-gray-700 dark:text-gray-200 max-h-60 overflow-y-auto">
                                @if($unreadCount > 0)
                                    @foreach(Auth::user()->unreadNotifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}" class="block mb-3 border-b pb-2 hover:bg-gray-50 dark:hover:bg-gray-600 rounded transition">
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-200 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                            <div class="flex justify-end">
                                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <p>No new notifications.</p>
                                @endif
                            </div>
                            <div class="py-1 text-sm text-gray-700 dark:text-gray-200 text-center">
                                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">View All</a>
                            </div>
                        </div>
                    </div>

                    {{-- User Avatar Dropdown --}}
                    <div class="relative">
                        <button id="dropdownUserAvatarButton" data-dropdown-toggle="dropdownUser" class="flex items-center text-sm rounded-full focus:ring-2 focus:ring-blue-500" type="button">
                            <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="user photo">
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownUser" class="hidden z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <div class="px-4 py-3">
                                <span class="block text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                                <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                            </div>
                            <ul class="py-2" aria-labelledby="dropdownUserAvatarButton">
                                <li>
                                    <a href="/profile/edit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Profile</a>
                                </li>
                                <li>
                                    <a href="{{ route('merchant.become') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Become a Merchant</a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.pets.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">My Pets</a>
                                </li>
                                <li>
                                    <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">My Bookings</a>
                                </li>
                                <li>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endauth
        </div>

        {{-- Mobile menu toggle button --}}
        <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none" aria-controls="navbar-default" aria-expanded="false">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Navigation Links --}}
        <div class="hidden w-full lg:flex lg:w-auto lg:order-1" id="navbar-default">
            <ul class="flex flex-col p-4 lg:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 lg:flex-row lg:space-x-8 lg:mt-0 lg:border-0 lg:bg-white dark:bg-gray-800 lg:dark:bg-gray-900">
                <li><a href="/" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">Home</a></li>
                <li><a href="{{ route('merchants.index') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">Merchant</a></li>
                <li><a href="/about" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">About</a></li>
                <li><a href="/contact" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
