<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-500 via-sky-500 to-amber-500 shadow-xl">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative flex items-center gap-8 p-8">
        {{-- Merchant Photo --}}
        <div class="flex-shrink-0">
            <img class="h-28 w-28 rounded-2xl ring-4 ring-white object-cover shadow-lg"
                src="{{ $profile->photo ? asset('storage/'.$profile->photo) : asset('images/placeholder-profile.png') }}"
                alt="{{ $profile->name ?? 'Merchant Photo' }}">
        </div>

        {{-- Merchant Info --}}
        <div class="text-white">
            <h2 class="text-lg font-medium opacity-90 tracking-wide uppercase">{{ $profile->role ?? 'Merchant' }}</h2>
            <h1 class="text-3xl font-extrabold tracking-tight drop-shadow">{{ $profile->name ?? 'Unnamed Merchant' }}</h1>
            <p class="mt-2 max-w-xl text-sm leading-relaxed opacity-90">{{ $profile->description ?? 'No description available.' }}</p>
            
            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm">
                @if($profile->phone)
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M2.88 3.37A2 2 0 014.67 2h1.66c.5 0 .94.33 1.06.81l.62 2.48a1.1 1.1 0 01-.28 1.05L6.7 7.4a11.8 11.8 0 005.9 5.9l1.05-1.03a1.1 1.1 0 011.05-.28l2.48.62c.48.12.81.56.81 1.06v1.66a2 2 0 01-1.37 1.79c-.9.3-1.86.3-2.79.03a18.5 18.5 0 01-9.61-9.61c-.27-.93-.27-1.89.03-2.79z"/></svg>
                        <span>{{ $profile->phone }}</span>
                    </div>
                @endif
                @if($profile->address)
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 016 6c0 4.5-6 10-6 10S4 12.5 4 8a6 6 0 016-6z"/></svg>
                        <span>{{ $profile->address }}</span>
                    </div>
                @endif
                @if($profile->registration_number)
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold">Reg:</span>
                        <span>{{ $profile->registration_number }}</span>
                    </div>
                @endif
                @if($profile->license_number)
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold">License:</span>
                        <span>{{ $profile->license_number }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- --- NEW CHAT BUTTON --- --}}
        {{-- It's placed inside the main relative container but outside the flex content --}}
        {{-- Assumes the $profile object has a `user_id` property linking to the user model --}}
        <div class="absolute top-6 right-6">
            <a href="{{ route('chat.index', $profile->user_id) }}" 
            class="inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2.5 text-sm font-semibold text-white
                    shadow-md backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus:ring-2 
                    focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-900">
                
                {{-- Chat Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm1.5 1a.5.5 0 000 1h8a.5.5 0 000-1h-8zm0 3a.5.5 0 000 1h5a.5.5 0 000-1h-5z"></path>
                    <path d="M2 12.5a.5.5 0 01.5-.5h4a.5.5 0 010 1h-4a.5.5 0 01-.5-.5z"></path>
                </svg>

                <span>Chat with Merchant</span>
            </a>
        </div>
    </div>
</div>
