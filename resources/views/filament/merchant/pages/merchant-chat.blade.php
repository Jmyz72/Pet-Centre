<x-filament-panels::page>
    {{-- 
        THE CORRECT STRUCTURE:
        1. The main container is a flexbox row (`flex`) with a fixed height (`h-[80vh]`).
        2. Its direct children (sidebar and chat window) will align horizontally.
    --}}
    <div class="flex h-[80vh] border rounded-lg shadow-lg bg-white dark:bg-gray-900 dark:border-gray-700 overflow-hidden">

        {{-- Sidebar: Customer list --}}
        {{-- It has a fixed width (`w-1/3`) and its own vertical scroll (`overflow-y-auto`) --}}
        <div class="w-1/3 border-r bg-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-y-auto">
            <h2 class="text-lg font-semibold p-4 border-b dark:border-gray-600">Customers</h2>
            <ul>
                @forelse ($chatPartners as $partner)
                    <li wire:click="selectCustomer({{ $partner->id }})" 
                        class="cursor-pointer group px-4 py-3 hover:bg-blue-100 dark:hover:bg-blue-600 flex items-center justify-between
                            {{ $selectedCustomerId == $partner->id ? 'bg-blue-200 dark:bg-blue-700' : '' }}">
                        
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold truncate text-gray-900 dark:text-gray-200">{{ $partner->name }}</p>
                            <p class="text-sm truncate text-gray-500 dark:text-gray-400">{{ $partner->email }}</p>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-2 text-gray-500 dark:text-gray-400">No messages yet</li>
                @endforelse
            </ul>
        </div>

        {{-- Chat window --}}
        {{-- 
            1. `flex-1`: It grows to fill the remaining horizontal space.
            2. `flex flex-col`: It becomes a vertical flex container for its own children (header, messages, form).
        --}}
        <div class="flex-1 flex flex-col">

            {{-- Header (Fixed Height) --}}
            <div class="p-4 border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-600 flex items-center justify-between shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-200">
                    @if($selectedCustomerId)
                        @php $customer = $chatPartners->firstWhere('id', $selectedCustomerId); @endphp
                        {{ $customer->name ?? 'Select a customer' }}
                    @else
                        Select a customer to chat
                    @endif
                </h2>
            </div>

            {{-- THE SCROLLABLE AREA --}}
            {{-- 
                1. `flex-1`: It grows to fill the available VERTICAL space.
                2. `min-h-0`: THIS IS THE CRITICAL FIX. It allows the element to shrink, enabling the scrollbar.
                3. `overflow-y-auto`: Adds the scrollbar only when needed.
            --}}
            <div 
                class="flex-1 p-4 overflow-y-auto bg-gray-50 dark:bg-gray-900 space-y-3 min-h-0"
                x-data="{}"
                x-init="$el.scrollTop = $el.scrollHeight"
                wire:poll.5s="loadMessages"
                @message-sent.window="$el.scrollTop = $el.scrollHeight"
            >
                @forelse ($messages as $msg)
                    <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="relative max-w-[70%]">
                            {{-- Chat bubble --}}
                            <div class="px-4 py-2 rounded-xl shadow
                                {{ $msg->sender_id == auth()->id() 
                                    ? 'bg-gray-900 text-black dark:bg-gb-200 dark:text-gray-900' 
                                    : 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-200' }}">
                                
                                @if($msg->message)
                                    <span>{{ $msg->message }}</span>
                                @endif

                                @if ($msg->image_path)
                                    @php $ext = strtolower(pathinfo($msg->image_path, PATHINFO_EXTENSION)); @endphp
                                    <div class="mt-2">
                                        @if (in_array($ext, ['png','jpg','jpeg', 'gif']))
                                            <a href="{{ Storage::url($msg->image_path) }}" target="_blank">
                                                {{-- THE ONLY CHANGE IS HERE: w-40 is now w-32 --}}
                                                <img src="{{ Storage::url($msg->image_path) }}" class="w-32 rounded hover:opacity-80 transition-opacity">
                                            </a>
                                        @elseif ($ext === 'pdf')
                                            <a href="{{ Storage::url($msg->image_path) }}" target="_blank"
                                            class="flex items-center gap-2 mt-2 px-3 py-1 bg-gray-300 dark:bg-gray-600 rounded hover:bg-gray-400 dark:hover:bg-gray-500 text-sm text-blue-600 dark:text-blue-300">
                                                <x-heroicon-o-document-text class="h-5 w-5"/>
                                                <span>View PDF</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Timestamp --}}
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 {{ $msg->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                                {{ $msg->created_at->format('M d, H:i') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-gray-500">
                        No messages in this conversation yet.
                    </div>
                @endforelse
            </div>

            {{-- Reply form (Fixed Height) --}}
            @if($selectedCustomerId)
            <div class="p-4 border-t bg-white dark:bg-gray-800 shadow-inner dark:border-gray-700">
                <form wire:submit.prevent="sendMessage" class="flex flex-col">
                    <div class="flex items-center space-x-2">
                        <input type="text" wire:model.defer="data.message"
                            class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-400 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700"
                            placeholder="Type a message..." autocomplete="off">

                        <input type="file" wire:model="data.file" class="hidden" id="file-upload">
                        <label for="file-upload" class="cursor-pointer flex items-center justify-center w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600">
                            <x-heroicon-o-paper-clip class="h-5 w-5 text-gray-700 dark:text-gray-200"/>
                        </label>

                        <button type="submit" class="bg-green-600 text-black px-6 py-2 rounded-full hover:bg-green-700 disabled:opacity-50">
                            Send
                        </button>
                    </div>
                    
                    <div wire:loading wire:target="data.file" class="text-sm text-gray-500 mt-2">Uploading...</div>

                    @if(isset($data['file']) && method_exists($data['file'], 'getClientOriginalName'))
                        <div class="mt-2 flex items-center space-x-2 text-gray-900 dark:text-gray-200">
                            <span class="text-sm truncate">Ready to send: {{ $data['file']->getClientOriginalName() }}</span>
                            <button type="button" wire:click="$set('data.file', null)" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600 text-sm font-bold">X</button>
                        </div>
                    @endif
                </form>
            </div>
            @endif

        </div>
    </div>
</x-filament-panels::page>