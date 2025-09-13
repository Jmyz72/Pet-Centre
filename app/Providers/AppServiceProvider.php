<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ChatRepositoryInterface; // <-- Add this line
use App\Repositories\EloquentChatRepository; // 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Add this binding
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
        //
    }
}
