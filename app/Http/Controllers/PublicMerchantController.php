<?php

namespace App\Http\Controllers;

use App\Models\MerchantProfile;
use Illuminate\Http\Request;

class PublicMerchantController extends Controller
{
    /**
     * Customer browse page: list merchants with optional search & role filters.
     */
    public function index(Request $request)
    {
        $profiles = MerchantProfile::query()
            // Optional: only show public profiles if the column exists in your schema
            ->when(schema_has_column('merchant_profiles', 'is_public') ?? false, function ($q) {
                $q->where('is_public', true);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search');
                $q->where(function ($w) use ($term) {
                    $w->where('name', 'like', "%{$term}%")
                      ->orWhere('address', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->string('role')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        // Render your browse/listing view (create this if you haven't yet)
        return view('merchant.profile.browse', compact('profiles'));
    }

    /**
     * Single merchant profile page: header + role-specific content.
     */
    public function show(MerchantProfile $merchantProfile)
    {
        // Eager-load relations conditionally to reduce queries
        $merchantProfile->loadMissing(['pets', 'services', 'packages']);

        $data = ['profile' => $merchantProfile];

        // Provide role-specific datasets if the relations/methods exist
        if ($merchantProfile->role === 'shelter' && method_exists($merchantProfile, 'pets')) {
            $data['pets'] = $merchantProfile->pets()
                ->when(method_exists($merchantProfile->pets()->getModel(), 'scopeAvailable'),
                    fn ($q) => $q->available())
                ->latest('created_at')
                ->paginate(12)
                ->withQueryString();
        }

        if ($merchantProfile->role === 'groomer' && method_exists($merchantProfile, 'packages')) {
            $data['packages'] = $merchantProfile->packages()
                ->when(method_exists($merchantProfile->packages()->getModel(), 'scopeActive'),
                    fn ($q) => $q->active())
                ->orderBy('name')
                ->get();
        }

        if ($merchantProfile->role === 'clinic' && method_exists($merchantProfile, 'services')) {
            $data['services'] = $merchantProfile->services()->orderBy('name')->get();
        }

        // Render the full content page (header + role section)
        return view('merchant.profile.index', $data);
    }
}

if (!function_exists('schema_has_column')) {
    /**
     * Lightweight helper to safely check a column's existence without failing in prod.
     */
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
