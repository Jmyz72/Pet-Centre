<?php
namespace App\Repositories;
use App\Interfaces\ChatRepositoryInterface;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentChatRepository implements ChatRepositoryInterface
{

    public function getChatPartnersFor(User $user): Collection
    {
        $sent = Message::where('sender_id', $user->id)->pluck('receiver_id');
        $received = Message::where('receiver_id', $user->id)->pluck('sender_id');
        $allIds = $sent->merge($received)->unique()->filter(fn($id) => $id != $user->id);
        return User::whereIn('id', $allIds)->whereHas('merchantProfile')->orderBy('name', 'asc')->get();
    }

    public function getMessagesBetween(int $userId1, int $userId2): Collection
    {
        return Message::where(fn($q) => $q->where('sender_id', $userId1)->where('receiver_id', $userId2))
            ->orWhere(fn($q) => $q->where('sender_id', $userId2)->where('receiver_id', $userId1))
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function createMessage(array $data): Message
    {
        return Message::create($data);
    }

}