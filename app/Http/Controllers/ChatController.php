<?php

namespace App\Http\Controllers;

use App\Interfaces\ChatRepositoryInterface; 
use App\Models\Message;
use App\Models\User;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{   

     public function index($receiverId = null)
    {
        $userId = auth()->id();
        $merchant = null;
        $messages = collect(); // Default to an empty collection

        if ($receiverId) {
            $merchant = User::where('id', $receiverId)->whereHas('merchantProfile')->firstOrFail();

            // Mark messages as read for this specific conversation
            Message::where('sender_id', $receiverId)
                ->where('receiver_id', $userId)
                ->update(['is_read' => true]);

            // Load the messages for this specific conversation
            $messages = Message::where(function ($q) use ($userId, $receiverId) {
                    $q->where('sender_id', $userId)->where('receiver_id', $receiverId);
                })->orWhere(function ($q) use ($userId, $receiverId) {
                    $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
                })->orderBy('created_at', 'asc')->get();
        }

        $sent = Message::where('sender_id', $userId)->pluck('receiver_id');
        $received = Message::where('receiver_id', $userId)->pluck('sender_id');
        $chatPartnerIds = $sent->merge($received)->unique()->filter();
        $merchants = User::whereIn('id', $chatPartnerIds)->whereHas('merchantProfile')->get();

        return view('chat.index', [
            'messages'     => $messages,
            'receiverId'   => $receiverId,
            'merchant'     => $merchant,
            'chatPartners' => $merchants,
        ]);
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
