<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Message;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;

class MerchantChat extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Chat';
    protected static ?string $title = 'Chat';
    protected static ?string $navigationGroup = 'Messaging';

    protected static string $view = 'filament.merchant.pages.merchant-chat';

    /** Selected customer ID */
    public ?int $selectedCustomerId = null;

    /** Customer list (who sent messages to merchant) */
    public Collection $chatPartners;

    /** Current messages with selected customer */
    public Collection $messages;

    /** Form state for new message */
    public ?array $data = [];

    public function mount(): void
    {
        $this->loadChatPartners();
        if (!$this->selectedCustomerId && $this->chatPartners->isNotEmpty()) {
            $this->selectedCustomerId = $this->chatPartners->first()->id;
        }
        $this->loadMessages();
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Textarea::make('message'),
                FileUpload::make('file'),
            ]);
    }

    protected function loadChatPartners(): void
    {
        $merchant = auth()->user();

        $customerIds = Message::where('receiver_id', $merchant->id)
            ->pluck('sender_id')
            ->unique();
        
        $sentToIds = Message::where('sender_id', $merchant->id)
            ->pluck('receiver_id')
            ->unique();
            
        $allIds = $customerIds->merge($sentToIds)->unique();

        $this->chatPartners = User::whereIn('id', $allIds)
            ->where('id', '!=', $merchant->id)
            ->orderBy('name', 'asc') // Add stable ordering
            ->get();
    }

    public function loadMessages(): void
    {
        if (!$this->selectedCustomerId) {
            $this->messages = collect();
            return;
        }

        $merchantId = auth()->id();
        $customerId = $this->selectedCustomerId;

        $this->messages = Message::where(function($q) use ($merchantId, $customerId) {
            $q->where('sender_id', $merchantId)->where('receiver_id', $customerId);
        })->orWhere(function($q) use ($merchantId, $customerId) {
            $q->where('sender_id', $customerId)->where('receiver_id', $merchantId);
        })
        ->orderBy('created_at', 'asc')
        ->get();
    }

    public function selectCustomer(int $customerId): void
    {
        $this->selectedCustomerId = $customerId;
        $this->loadMessages();
    }

    public function sendMessage(): void
    {
        $merchant = auth()->user();
        $customerId = $this->selectedCustomerId;

        if (!$customerId) {
            Notification::make()
                ->title('No customer selected')
                ->warning()
                ->send();
            return;
        }

        $messageText = Arr::get($this->data, 'message');
        $file = Arr::get($this->data, 'file');

        $filePath = null;
        if ($file) {
            $filePath = $file->store('chat_files', 'public');
        }

        if (empty($messageText) && !$filePath) {
            return;
        }

        Message::create([
            'sender_id'   => $merchant->id,
            'receiver_id' => $customerId,
            'message'     => $messageText ?? '',
            'image_path'  => $filePath,
        ]);

        $this->data = [];
        $this->loadMessages();

        // Dispatch an event to the browser after sending
        $this->dispatch('message-sent');
    }
}