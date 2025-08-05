<!-- resources/views/layouts/header.blade.php -->
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
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Profile</a>
                            </li>
                            <li>
                                <a href="{{ route('merchant.apply') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Become a Merchant</a>
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
                <li><a href="/pets" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">Pets</a></li>
                <li><a href="/services" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">Services</a></li>
                <li><a href="/contact" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
