<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        try {
            // Find the user
            $user = User::findOrFail($request->route('id'));

            // Use Laravel's standard hash verification for email verification URLs
            // Laravel generates hash as: sha1($user->getEmailForVerification())
            $expectedHash = sha1($user->getEmailForVerification());

            if (!hash_equals((string) $request->route('hash'), $expectedHash)) {
                return view('auth.verify-email-error', [
                    'error' => 'Invalid verification link. Please request a new verification email.'
                ]);
            }

            if ($user->hasVerifiedEmail()) {
                return view('auth.verify-email-success', [
                    'message' => 'Your email is already verified. You can now login.',
                    'user' => $user,
                    'alreadyVerified' => true
                ]);
            }

            // Mark email as verified and trigger event
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return view('auth.verify-email-success', [
                'message' => 'Your email has been verified successfully! You have been assigned the customer role and can now login.',
                'user' => $user,
                'justVerified' => true
            ]);

        } catch (\Exception $e) {
            return view('auth.verify-email-error', [
                'error' => 'An error occurred during email verification. Please try again or request a new verification email.'
            ]);
        }
    }
}
