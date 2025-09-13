<?php
namespace App\Interfaces;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

interface ChatRepositoryInterface 
{
    public function getChatPartnersFor(User $user): Collection;
    public function getMessagesBetween(int $userId1, int $userId2): Collection;
    public function createMessage(array $data): Message;
}