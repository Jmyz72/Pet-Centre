<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user has verified their email
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();

            // Send verification email
            $user->sendEmailVerificationNotification();

            // Stay on login page with error message
            return back()->withErrors([
                'email' => 'Please verify your email before logging in. A verification email has been sent to your email address.'
            ]);
        }

        // Admin -> Filament Admin panel
        if ($user->hasRole('admin')) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        // Groomer / Clinic / Shelter -> Filament Merchant panel
        if ($user->hasAnyRole(['groomer', 'clinic', 'shelter'])) {
            return redirect()->route('filament.merchant.pages.dashboard');
        }

        // Customer -> Welcome page (public landing)
        if ($user->hasRole('customer')) {
            return redirect()->to('/');
        }

        // Fallback: go home
        return redirect()->intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
