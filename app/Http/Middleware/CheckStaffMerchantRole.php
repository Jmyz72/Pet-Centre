<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffMerchantRole
{
    /**
     * Handle an incoming request for staff-related resources.
     * Only groomer and clinic merchants can manage staff.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has groomer or clinic roles (staff management only)
        $allowedRoles = ['groomer', 'groomer_merchant', 'clinic', 'clinic_merchant'];
        
        $hasAllowedRole = false;
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAllowedRole = true;
                break;
            }
        }

        if (!$hasAllowedRole) {
            abort(403, 'Access denied. Staff management is only available for groomer and clinic merchants.');
        }

        return $next($request);
    }
}
