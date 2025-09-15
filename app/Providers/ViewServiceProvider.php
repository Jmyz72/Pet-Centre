<?php

namespace App\Providers;

use App\Http\View\Composers\NavigationComposer; // <-- Import the composer
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.partials.navigation', NavigationComposer::class);
    }
}
