<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use App\Listeners\AssignCustomerRole;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Verified::class, AssignCustomerRole::class);
    }
}
