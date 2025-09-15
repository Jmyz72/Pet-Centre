<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAuthenticationAttempt
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($event)
    {
        $email = $event->credentials['email'] ?? ($event->user->email ?? 'N/A');
        $ip = $this->request->ip();

        if ($event instanceof Login) {
            // Log a successful login
            Log::info("Login Succeeded: User [{$email}] from IP [{$ip}]");
        } elseif ($event instanceof Failed) {
            // Log a failed login attempt
            Log::warning("Login FAILED: Attempt for user [{$email}] from IP [{$ip}]");
        }
    }
}