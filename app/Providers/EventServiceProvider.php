<?php

namespace App\Providers;

use App\Events\MessageSent; // <-- Import
use App\Listeners\SendNewMessageNotification; // <-- Import
use Illuminate\Auth\Events\Registered;
use App\Interfaces\ChatRepositoryInterface; // <-- Import Interface
use App\Repositories\EloquentChatRepository; // <-- Import Implementation
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * --- OBSERVER PATTERN, STEP 3: THE CONNECTION ---
     * This line tells Laravel: "When you hear a MessageSent event,
     * please execute the SendNewMessageNotification listener."
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // Add this line
        MessageSent::class => [
            SendNewMessageNotification::class,
        ],
    ];

    public $bindings = [ 
        ChatRepositoryInterface::class => EloquentChatRepository::class,
    ];

    // ...
}