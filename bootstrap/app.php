<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;  
use Illuminate\Http\Request;              
use Illuminate\Support\Facades\RateLimiter; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            RateLimiter::for('chat', function (Request $request) {
                // Allow 10 messages per minute per authenticated user.
                return Limit::perMinute(10)->by($request->user()->id);
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        $exceptions->reportable(function (AuthorizationException $e, $request) {
            $user = $request->user();
            $userId = $user ? $user->id : 'Guest';
            $ip = $request->ip();
            $url = $request->fullUrl();

            // Log the access control failure with critical details.
            Log::warning("Access Control Violation: User [{$userId}] from IP [{$ip}] attempted to access unauthorized URL [{$url}]");
        });
        
    })->create();
