<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use App\Listeners\AssignCustomerRole;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\ChatRepositoryInterface;
use App\Repositories\EloquentChatRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ChatRepositoryInterface::class,
            EloquentChatRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Verified::class, AssignCustomerRole::class);
    }
}
