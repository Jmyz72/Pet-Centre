<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\ChatRepositoryInterface;
use App\Rules\VirusScan; 
use App\Jobs\ProcessChatMessage;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    const DEFAULT_PAGINATION_SIZE = 50; // Increased for chat history

    /**
     * THIS IS THE MISSING METHOD
     * Fetch messages for a conversation.
     */
    public function index(Request $request, $partnerId)
    {
        $userId = Auth::id();
        $sinceId = $request->input('since', 0); // Get the 'since' parameter

        $messagesQuery = Message::where(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $userId)->where('receiver_id', $partnerId);
            })
            ->orWhere(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $partnerId)->where('receiver_id', $userId);
            });

        // If the client is asking for messages since a certain ID, use it
        if ($sinceId > 0) {
            $messagesQuery->where('id', '>', $sinceId);
        }

        $messages = $messagesQuery->orderBy('created_at', 'asc')->get();

        // We return a simple array, not a paginated one, for this polling logic
        return response()->json(['data' => $messages]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:5000',
            'file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,application/pdf',
                'max:5120',
                new VirusScan()
            ],
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('chat_files', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $data['receiver_id'],
            'message' => $data['message'] ?? '',
            'image_path' => $path,
        ]);

        // Dispatch the event to notify any listeners
        MessageSent::dispatch($message);

        return response()->json($message, 201); // Respond with the created message
    }

    public function update(Request $request, Message $message)
    {
        // 1. Authorization Check: Ensure the user owns the message
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 2. Validation: Ensure the new message text is valid
        $data = $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        // 3. Update the message
        $message->update($data);

        // 4. Return the updated message as a JSON response
        return response()->json(['message' => $message]);
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $message->delete();
        return response()->json(null, 204);
    }
}