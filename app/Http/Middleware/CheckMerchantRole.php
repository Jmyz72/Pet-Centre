<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMerchantRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has any of the allowed merchant roles
        $allowedRoles = ['groomer', 'groomer_merchant', 'clinic', 'clinic_merchant', 'shelter', 'shelter_merchant'];
        
        $hasAllowedRole = false;
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAllowedRole = true;
                break;
            }
        }

        if (!$hasAllowedRole) {
            abort(403, 'Access denied. Merchant access required (groomer, clinic, or shelter).');
        }

        return $next($request);
    }
}
