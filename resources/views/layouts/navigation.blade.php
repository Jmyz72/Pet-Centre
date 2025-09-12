<nav class="bg-white border-b border-gray-200 px-4 py-3 dark:bg-gray-800 shadow-sm">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
        <!-- Logo -->
        <a href="/" class="flex items-center space-x-2 rtl:space-x-reverse">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">PetCentre</span>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <a href="/" class="text-gray-700 hover:text-blue-600 dark:text-gray-200 dark:hover:text-white font-medium transition-colors">Home</a>
            <a href="/pets" class="text-gray-700 hover:text-blue-600 dark:text-gray-200 dark:hover:text-white font-medium transition-colors">Pets</a>
            <a href="/services" class="text-gray-700 hover:text-blue-600 dark:text-gray-200 dark:hover:text-white font-medium transition-colors">Services</a>
            <a href="/about" class="text-gray-700 hover:text-blue-600 dark:text-gray-200 dark:hover:text-white font-medium transition-colors">About</a>
            <a href="/contact" class="text-gray-700 hover:text-blue-600 dark:text-gray-200 dark:hover:text-white font-medium transition-colors">Contact</a>
        </div>

        <!-- Right Side (Guest or Auth) -->
        <div class="flex items-center lg:order-2 space-x-4 rtl:space-x-reverse">
            <!-- Mobile menu toggle button -->
            <button id="mobile-menu-button" type="button" class="lg:hidden p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            @guest
                <!-- Guest Links -->
                <div class="hidden lg:flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 dark:text-gray-200 dark:hover:text-white font-medium transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-4 py-2 transition-colors shadow-sm">Register</a>
                </div>
            @endguest

            @auth
                <!-- Notification Dropdown -->
                <div class="relative hidden lg:block">
                    <button id="notification-button" class="relative p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unreadCount = Auth::user()->unreadNotifications->count())
                            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notification Dropdown Content -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="block p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($notification->data['message'] ?? '', 60) }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                </a>
                            @empty
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('notifications.index') }}" class="block text-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium py-2">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Avatar Dropdown -->
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none">
                        <img class="w-8 h-8 rounded-full border-2 border-gray-200 dark:border-gray-600" 
                             src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3B82F6&color=fff' }}" 
                             alt="{{ Auth::user()->name }}">
                        <span class="hidden lg:block text-gray-700 dark:text-gray-200 font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Profile</a>
                            <a href="{{ route('merchant.become') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Become Merchant</a>
                        </div>
                        <div class="py-1 border-t border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="px-4 py-3 space-y-2">
            <a href="/" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg">Home</a>
            <a href="/pets" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg">Pets</a>
            <a href="/services" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg">Services</a>
            <a href="/about" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg">About</a>
            <a href="/contact" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg">Contact</a>
            
            @guest
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                    <a href="{{ route('login') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 rounded-lg">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 px-3 bg-blue-600 text-white hover:bg-blue-700 rounded-lg">Register</a>
                </div>
            @endguest
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Notification dropdown
    const notificationButton = document.getElementById('notification-button');
    const notificationDropdown = document.getElementById('notification-dropdown');
    
    if (notificationButton && notificationDropdown) {
        notificationButton.addEventListener('click', function() {
            notificationDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
    }

    // User dropdown
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function() {
            userDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
</script>