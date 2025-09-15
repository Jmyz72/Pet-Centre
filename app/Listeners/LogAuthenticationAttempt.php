<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogAuthenticationAttempt
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($event)
    {
        // Get the email and IP address from the request
        $email = $event->credentials['email'] ?? ($event->user->email ?? 'N/A');
        $ip = $this->request->ip();

        if ($event instanceof Login) {
            // --- LOG SUCCESSFUL LOGIN TO THE DATABASE ---
            activity()
               ->causedBy($event->user) // Link the log to the user who logged in
               ->log("Login Succeeded from IP: {$ip}");

        } elseif ($event instanceof Failed) {
            // --- LOG FAILED LOGIN TO THE DATABASE ---
            activity()
               ->withProperties(['email' => $email, 'ip_address' => $ip])
               ->log('Login Failed');
        }
    }
}