@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
    <div class="flex h-[80vh] border rounded-lg shadow-lg bg-white overflow-hidden">
        
        {{-- Sidebar: merchants list --}}
        <div class="w-1/4 border-r bg-gray-100 overflow-y-auto">
            <h2 class="text-lg font-semibold p-4 border-b">Chats</h2>
            <ul>
                @forelse ($chatPartners as $partner)
                <li class="relative group px-4 py-2 hover:bg-blue-100 
                    {{ $receiverId == $partner->id ? 'bg-blue-200 font-semibold' : '' }} flex items-center justify-between">

                <a href="{{ route('chat.index', $partner->id) }}" class="flex-1 flex items-center space-x-3 truncate">
                    
                    {{-- Merchant profile photo --}}
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 rounded-2xl ring-2 ring-white object-cover shadow"
                            src="{{ $partner->merchantProfile->photo ? asset('storage/' . $partner->merchantProfile->photo) : asset('images/placeholder-profile.png') }}"
                            alt="{{ $partner->merchantProfile->shop_name ?? $partner->name }}">
                    </div>

                    {{-- Merchant name --}}
                    <span class="truncate">{{ $partner->merchantProfile->shop_name ?? $partner->name }}</span>
                </a>

                {{-- Remove button (if selected chat) --}}
                @if($receiverId == $partner->id)
                <form action="{{ route('chat.delete', $partner->id) }}" method="POST" 
                    onsubmit="return confirm('Delete entire chat with this merchant?')"
                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-6 h-6 flex items-center justify-center rounded-full 
                                bg-red-500 text-white hover:bg-red-600 text-xs font-bold">
                        X
                    </button>
                </form>
                @endif
                </li>
                @empty
                <li class="px-4 py-2 text-gray-500">No chats yet</li>
                @endforelse
            </ul>
        </div>

        {{-- Chat window --}}
        <div class="flex-1 flex flex-col">
            <div class="p-4 border-b bg-gray-50 flex items-center justify-between shadow-sm">
                <h2 class="text-lg font-bold flex items-center justify-between">
                    <span>{{ $merchant->merchantProfile->shop_name ?? $merchant->name }}</span>
                    <a href="{{ route('merchants.show', $merchant->merchantProfile->id) }}" 
                    class="ml-3 inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 
                            text-white text-sm font-medium rounded-full shadow hover:from-blue-600 hover:to-indigo-600 
                            transition duration-200">
                        View Profile
                    </a>
                </h2>
            </div>

            {{-- Messages --}}
            <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-3 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                @foreach ($messages as $msg)
                <div class="flex mb-3 {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="relative max-w-[70%]">
                        {{-- Chat bubble --}}
                        <div class="px-4 py-2 rounded-xl 
                            {{ $msg->sender_id == Auth::id() ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-200 shadow' }}">

                            <span class="message-text" data-id="{{ $msg->id }}">{{ $msg->message }}</span>

                            @if ($msg->image_path)
                                @php
                                    $ext = pathinfo($msg->image_path, PATHINFO_EXTENSION);
                                @endphp

                                @if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg']))
                                    <a href="{{ asset('storage/' . $msg->image_path) }}" target="_blank" download>
                                        <img src="{{ asset('storage/' . $msg->image_path) }}" class="w-40 rounded mt-2 hover:opacity-80 transition-opacity">
                                    </a>
                                @elseif (strtolower($ext) === 'pdf')
                                    <a href="{{ asset('storage/' . $msg->image_path) }}" target="_blank" download
                                    class="block mt-2 px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm text-blue-600">
                                        📄 Download PDF
                                    </a>
                                @endif
                            @endif

                            {{-- Menu icon --}}
                            @if ($msg->sender_id == Auth::id())
                            <div class="absolute top-1 right-1">
                                <button class="bubble-menu-btn text-white font-bold text-sm hover:text-gray-200">
                                    ⋮
                                </button>
                                <div class="bubble-menu hidden absolute right-0 mt-1 w-24 bg-white border rounded shadow-lg z-10">
                                    <button class="edit-btn block w-full text-left px-2 py-1 text-blue-600 text-xs hover:bg-gray-100" data-id="{{ $msg->id }}">
                                        Edit
                                    </button>
                                    <button class="delete-btn block w-full text-left px-2 py-1 text-red-600 text-xs hover:bg-gray-100" data-id="{{ $msg->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Timestamp outside bubble --}}
                        <div class="text-xs text-gray-500 mt-1 text-right">
                            {{ $msg->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Message form --}}
            <form id="chat-form" class="p-4 border-t flex flex-col bg-white shadow-inner rounded-b-lg">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $receiverId }}">

                {{-- File preview container --}}
                <div id="file-preview-container" class="hidden mb-2 flex items-center space-x-2">
                    <img id="preview-img" src="" class="w-10 h-10 object-cover rounded hidden">
                    <div id="preview-name" class="text-sm text-gray-700 truncate"></div>
                    <button type="button" id="remove-file" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                </div>

                <div class="flex items-center">
                    {{-- Text input --}}
                    <input type="text" name="message" id="message" 
                           class="flex-1 border rounded-full p-3 mr-2 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                           placeholder="Type a message..."
                           autocomplete="off">

                    {{-- File input --}}
                    <label for="file" class="cursor-pointer flex items-center justify-center w-10 h-10 bg-gray-200 rounded hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 10.828V21H6v-2.172M6 3h12a1 1 0 011 1v12a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z" />
                        </svg>
                        <input type="file" name="file" id="file" class="hidden" accept="image/*,application/pdf">
                    </label>

                    {{-- Send button --}}
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 ml-2">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Send new message
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();

        const messageVal = $('#message').val().trim();
        const fileInput = $('#file')[0].files[0];

        // If both text and file are empty, do nothing
        if (!messageVal && !fileInput) return;

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('chat.send') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                // Clear input and file preview
                $('#message').val('');
                $('#file').val('');
                $('#preview-img').attr('src', '').addClass('hidden');
                $('#preview-name').text('');
                $('#file-preview-container').addClass('hidden');

                location.reload(); // or append dynamically
            },
            error: function() {
                alert("Error sending message");
            }
        });
    });

    // Edit message
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let currentText = $("span.message-text[data-id='" + id + "']").text();
        let newText = prompt("Edit your message:", currentText);

        if (newText && newText.trim() !== "") {
            $.ajax({
                url: "/chat/message/" + id,
                type: "PUT",
                data: {_token: "{{ csrf_token() }}", message: newText},
                success: function(res) {
                    $("span.message-text[data-id='" + id + "']").text(res.message.message);
                },
                error: function() { alert("Failed to edit message"); }
            });
        }
    });

    // Toggle bubble menu
    $(document).on('click', '.bubble-menu-btn', function(e) {
        e.stopPropagation();
        let menu = $(this).siblings('.bubble-menu');
        $('.bubble-menu').not(menu).addClass('hidden');
        menu.toggleClass('hidden');
    });
    $(document).on('click', function() { $('.bubble-menu').addClass('hidden'); });

    // Delete message
    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this message?")) {
            $.ajax({
                url: "/chat/message/" + id,
                type: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function() {
                    $("span.message-text[data-id='" + id + "']").closest("div.flex").remove();
                },
                error: function() { alert("Failed to delete message"); }
            });
        }
    });

    // Preview selected file (single image or PDF)
    $('#file').on('change', function() {
        const file = this.files[0];
        const previewImg = $('#preview-img');
        const previewName = $('#preview-name');

        if (file) {
            previewName.text(file.name);
            $('#file-preview-container').removeClass('hidden');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.attr('src', e.target.result).removeClass('hidden');
                }
                reader.readAsDataURL(file);
            } else if (file.type === "application/pdf") {
                // PDF: show PDF icon instead
                previewImg.attr('src', '/icons/pdf-icon.png').removeClass('hidden'); // Replace with your icon path
            } else {
                // Unsupported file type: clear input
                $('#file').val('');
                $('#preview-img').attr('src', '').addClass('hidden');
                $('#preview-name').text('');
                $('#file-preview-container').addClass('hidden');
                alert("Only images or PDF files are allowed.");
            }
        }
    });

    // Remove selected file
    $('#remove-file').on('click', function() {
        $('#file').val('');
        $('#preview-img').attr('src', '').addClass('hidden');
        $('#preview-name').text('');
        $('#file-preview-container').addClass('hidden');
    });

});
</script>

@endsection
