@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
    {{-- Main two-column layout container --}}
    <div class="flex h-[80vh] border rounded-lg shadow-lg bg-white overflow-hidden">
        
        {{-- Sidebar: merchants list (This is always visible) --}}
        <div class="w-1/4 border-r bg-gray-100 overflow-y-auto">
            <h2 class="text-lg font-semibold p-4 border-b">Chats</h2>
            <ul>
                @forelse ($chatPartners as $partner)
                <li class="relative group px-4 py-2 hover:bg-blue-100 
                    {{ $receiverId == $partner->id ? 'bg-blue-200 font-semibold' : '' }} flex items-center justify-between">

                <a href="{{ route('chat.index', $partner->id) }}" class="flex-1 flex items-center space-x-3 truncate">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 rounded-2xl ring-2 ring-white object-cover shadow"
                            src="{{ $partner->merchantProfile->photo ? asset('storage/' . $partner->merchantProfile->photo) : asset('images/placeholder-profile.png') }}"
                            alt="{{ $partner->merchantProfile->shop_name ?? $partner->name }}">
                    </div>
                    <span class="truncate">{{ $partner->merchantProfile->shop_name ?? $partner->name }}</span>
                </a>
                </li>
                @empty
                <li class="px-4 py-2 text-gray-500">No chats yet</li>
                @endforelse
            </ul>
        </div>

        {{-- Chat window (This container is always visible) --}}
        <div class="flex-1 flex flex-col">
            
            {{-- We check for the $merchant variable to decide what to render INSIDE this container --}}
            @if ($merchant)
                {{-- If a merchant is selected, show the full, original chat interface --}}
                
                {{-- Header --}}
                <div class="p-4 border-b bg-gray-50 flex items-center justify-between shadow-sm">
                    <h2 class="text-lg font-bold flex items-center justify-between">
                        <span>{{ $merchant->merchantProfile->shop_name ?? $merchant->name }}</span>
                        <a href="{{ route('merchants.show', $merchant->merchantProfile->id) }}" 
                           class="ml-3 inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm font-medium rounded-full shadow hover:from-blue-600 hover:to-indigo-600 transition duration-200">
                            View Profile
                        </a>
                    </h2>
                </div>

                {{-- Messages --}}
                <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-3">
                    @foreach ($messages as $msg)
                    <div class="flex mb-3 {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="relative max-w-[70%] group">
                            <div class="px-4 py-2 rounded-xl {{ $msg->sender_id == Auth::id() ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-200 shadow' }}">
                                <span class="message-text" data-id="{{ $msg->id }}">{{ $msg->message }}</span>
                                @if ($msg->image_path)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $msg->image_path) }}" target="_blank"><img src="{{ asset('storage/' . $msg->image_path) }}" class="w-40 rounded mt-2"></a>
                                </div>
                                @endif
                                @if ($msg->sender_id == Auth::id())
                                <div class="absolute top-1 right-1">
                                    <button class="bubble-menu-btn text-white font-bold text-sm hover:text-gray-200">⋮</button>
                                    <div class="bubble-menu hidden absolute right-0 mt-1 w-24 bg-white border rounded shadow-lg z-10">
                                        <button class="edit-btn block w-full text-left px-2 py-1 text-blue-600 text-xs" data-id="{{ $msg->id }}">Edit</button>
                                        <button class="delete-btn block w-full text-left px-2 py-1 text-red-600 text-xs" data-id="{{ $msg->id }}">Delete</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 mt-1 text-right">{{ $msg->created_at->format('M d, H:i') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Message form --}}
                <form id="chat-form" class="p-4 border-t flex flex-col bg-white shadow-inner rounded-b-lg">
                    <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
                    <div id="file-preview-container" class="hidden mb-2 flex items-center space-x-2">
                        <img id="preview-img" src="" class="w-10 h-10 object-cover rounded hidden">
                        <div id="preview-name" class="text-sm text-gray-700 truncate"></div>
                        <button type="button" id="remove-file" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                    </div>
                    <div class="flex items-center">
                        <input type="text" name="message" id="message" class="flex-1 border rounded-full p-3 mr-2" placeholder="Type a message..." autocomplete="off">
                        <label for="file" class="cursor-pointer flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300">
                            <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 10.828V19a2 2 0 01-2 2H8a2 2 0 01-2-2V5a2 2 0 012-2h6.172a2 2 0 011.414.586z"></path></svg>
                        </label>
                        <input type="file" name="file" id="file" class="hidden" accept="image/*,application/pdf">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 ml-2">Send</button>
                    </div>
                </form>

            @else
                {{-- If no merchant is selected (/chat route), show the empty state --}}
                <div class="flex-1 flex items-center justify-center h-full">
                    <div class="text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Select a conversation</h3>
                        <p class="mt-1 text-sm text-gray-500">Choose a merchant from the list on the left to view the chat.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const partnerId = {{ $receiverId ?? 'null' }};
    if (partnerId === null) {
        return; // Do nothing if no chat is selected
    }
    
    const chatBox = $('#chat-box');
    const currentUserId = {{ Auth::id() }};
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function scrollToBottom() {
        setTimeout(() => { chatBox.scrollTop(chatBox[0].scrollHeight); }, 100);
    }
    scrollToBottom();

    function appendMessage(message) {
        if ($(`.message-text[data-id="${message.id}"]`).length > 0) return;

        let fileHtml = '';
        if (message.image_path) {
            const fileUrl = `/storage/${message.image_path}`;
            // --- FIX #2: More robust check for PDF files ---
            if (message.image_path.toLowerCase().endsWith('.pdf')) {
                fileHtml = `<div class="mt-2"><a href="${fileUrl}" target="_blank" class="flex items-center gap-1 text-blue dark:text-blue-300 hover:underline"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> View PDF</a></div>`;
            } else {
                fileHtml = `<div class="mt-2"><a href="${fileUrl}" target="_blank"><img src="${fileUrl}" class="w-40 rounded"></a></div>`;
            }
        }
        
        const formattedTime = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        let menuHtml = '';
        if (message.sender_id == currentUserId) {
            menuHtml = `
                <div class="absolute top-1 right-1">
                    <button class="bubble-menu-btn text-white font-bold text-sm hover:text-gray-200">⋮</button>
                    <div class="bubble-menu hidden absolute right-0 mt-1 w-24 bg-white border rounded shadow-lg z-10">
                        <button class="edit-btn block w-full text-left px-2 py-1 text-blue-600 text-xs hover:bg-gray-100" data-id="${message.id}">Edit</button>
                        <button class="delete-btn block w-full text-left px-2 py-1 text-red-600 text-xs hover:bg-gray-100" data-id="${message.id}">Delete</button>
                    </div>
                </div>
            `;
        }

        const messageHtml = `
            <div class="flex mb-3 ${message.sender_id == currentUserId ? 'justify-end' : 'justify-start'}">
                <div class="relative max-w-[70%] group">
                    <div class="px-4 py-2 rounded-xl ${message.sender_id == currentUserId ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-200 shadow'}">
                        <span class="message-text" data-id="${message.id}">${message.message || ''}</span>
                        ${fileHtml}
                        ${menuHtml}
                    </div>
                    <div class="text-xs text-gray-500 mt-1 text-right">${formattedTime}</div>
                </div>
            </div>
        `;
        chatBox.append(messageHtml);
        scrollToBottom();
    }
    
    function fetchNewMessages() {
        let urlTemplate = "{{ route('api.chat.messages.index', ['partnerId' => ':id']) }}";
        let fetchUrl = urlTemplate.replace(':id', partnerId) + `?since=${lastMessageId}`;

        $.ajax({
            url: fetchUrl,
            type: "GET",
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    response.data.forEach(appendMessage);
                    lastMessageId = response.data[response.data.length - 1].id;
                }
            },
            error: function(xhr) { console.error("Polling error:", xhr); }
        });
    }

    (function poll() {
       setTimeout(() => { fetchNewMessages(); poll(); }, 5000);
    })();
    
    // --- SEND MESSAGE LOGIC (FULLY CORRECTED) ---
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        const sendButton = $(this).find('button[type="submit"]');
        let formData = new FormData(this);

        // Disable button to prevent double-sending
        sendButton.prop('disabled', true).text('Sending...');

        $.ajax({
            url: "{{ route('api.chat.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(newMessage) {
                appendMessage(newMessage);
                lastMessageId = newMessage.id;
                
                // --- FIX #1: RESET THE FORM AND PREVIEW ---
                $('#chat-form')[0].reset();
                $('#file-preview-container').addClass('hidden').empty();
            },
            error: function(xhr) {
                alert("Error: " + (xhr.responseJSON.message || "Could not send message."));
            },
            complete: function() {
                // --- FIX #1: RE-ENABLE THE BUTTON AND RESET TEXT ---
                sendButton.prop('disabled', false).text('Send');
            }
        });
    });

    // --- ALL OTHER LISTENERS (UNCHANGED BUT CONFIRMED CORRECT) ---
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let messageSpan = $("span.message-text[data-id='" + id + "']");
        let currentText = messageSpan.text();
        let newText = prompt("Edit your message:", currentText);
        if (newText && newText.trim() !== "" && newText.trim() !== currentText) {
            let urlTemplate = "{{ route('api.chat.update', ['message' => ':id']) }}";
            let updateUrl = urlTemplate.replace(':id', id);
            $.ajax({
                url: updateUrl, type: "PUT", data: { message: newText },
                success: function(response) { messageSpan.text(response.message.message); },
                error: function() { alert("Failed to edit message."); }
            });
        }
    });

    $(document).on('click', '.delete-btn', function() {
        let messageId = $(this).data('id');
        let messageElement = $(this).closest('.flex.mb-3');
        if (confirm("Are you sure?")) {
            let urlTemplate = "{{ route('api.chat.destroy', ['message' => ':id']) }}";
            let deleteUrl = urlTemplate.replace(':id', messageId);
            $.ajax({
                url: deleteUrl, type: "DELETE",
                success: function() {
                    messageElement.fadeOut(300, function() { $(this).remove(); });
                },
                error: function() { alert("Failed to delete message."); }
            });
        }
    });
    
    $(document).on('click', '.bubble-menu-btn', function(e) { e.stopPropagation(); let menu = $(this).siblings('.bubble-menu'); $('.bubble-menu').not(menu).addClass('hidden'); menu.toggleClass('hidden'); });
    $(document).on('click', function() { $('.bubble-menu').addClass('hidden'); });

    $('#file').on('change', function() {
        const file = this.files[0];
        const previewContainer = $('#file-preview-container');
        if (!file) return;
        let previewHtml = `<div class="flex items-center space-x-2"><div class="text-sm text-gray-700 truncate">${file.name}</div><button type="button" id="remove-file" class="text-red-600 hover:text-red-800 text-sm">Remove</button></div>`;
        previewContainer.html(previewHtml).removeClass('hidden');
    });

    $(document).on('click', '#remove-file', function() {
        $('#file').val('');
        $('#file-preview-container').addClass('hidden').empty();
    });
});
</script>
@endsection