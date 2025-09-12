<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($receiverId)
    {
        $userId = auth()->id();

        // Check if receiver is a merchant
        $merchant = User::where('id', $receiverId)
            ->whereHas('merchantProfile')
            ->first();

        if (!$merchant) {
            abort(404, 'Merchant not found');
        }

        // Fetch chat history
        $messages = Message::where(function ($q) use ($userId, $receiverId) {
                $q->where('sender_id', $userId)
                ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($userId, $receiverId) {
                $q->where('sender_id', $receiverId)
                ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Build list of user IDs this person has chatted with
        $sent = Message::where('sender_id', $userId)->pluck('receiver_id')->toArray();
        $received = Message::where('receiver_id', $userId)->pluck('sender_id')->toArray();

        $chatPartnerIds = array_unique(array_merge($sent, $received));
        $chatPartnerIds = array_values(array_filter($chatPartnerIds, function($id) use ($userId) {
            return $id && $id != $userId;
        }));

        $merchantIds = MerchantProfile::whereIn('user_id', $chatPartnerIds)->pluck('user_id');
        $merchants = User::whereIn('id', $merchantIds)->get();

        return view('chat.index', [
            'messages'     => $messages,
            'receiverId'   => $receiverId,
            'merchant'     => $merchant,
            'chatPartners' => $merchants, // ✅ alias for Blade
        ]);
    }


    // Send message
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat_files', 'public'); // store in storage/app/public/chat_files
        }


        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'image_path' => $path,
        ]);

        return response()->json($message);
    }

    // Update (edit) a message
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        // Only sender can edit
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        $message->update([
            'message' => $request->message
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Delete a message
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        // Only sender can delete
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function deleteChat($partnerId)
    {
        $userId = auth()->id();

        // Delete all messages between the logged-in user and the partner
        Message::where(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $userId)
                ->where('receiver_id', $partnerId);
            })
            ->orWhere(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $partnerId)
                ->where('receiver_id', $userId);
            })
            ->delete();

        return redirect()->route('chat.index', ['receiverId' => $partnerId])
            ->with('status', 'Chat deleted successfully.');
    }


}
