<?php

namespace App\Http\Controllers;

use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublicMerchantController extends Controller
{
    /**
     * Customer browse page: list merchants with optional search & role filters.
     */
    public function index(Request $request)
    {
        // Normalize & validate inputs
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role'   => ['nullable', 'string', Rule::in(['clinic', 'shelter', 'groomer'])],
            'page'   => ['nullable', 'integer', 'min:1'],
        ]);
        
        $search = trim((string) ($validated['search'] ?? ''));
        $role   = $validated['role'] ?? null;

        $profiles = MerchantProfile::query()
            // Lightweight projection for list view
            ->select(['id', 'name', 'role', 'phone', 'address', 'photo', 'created_at'])
            // Optional counts shown on cards if you later want them (kept cheap)
            ->when(method_exists(MerchantProfile::class, 'packages'), fn ($q) => $q->withCount('packages'))
            ->when(method_exists(MerchantProfile::class, 'services'), fn ($q) => $q->withCount('services'))
            ->when(method_exists(MerchantProfile::class, 'pets'),     fn ($q) => $q->withCount('pets'))
            // Filters
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->when($role, fn ($q) => $q->where('role', $role))
            // Sorting: name asc for stable list; newest first if searching
            ->when($search !== '', fn ($q) => $q->latest(), fn ($q) => $q->orderBy('name'))
            ->paginate(10)
            ->withQueryString();

        // Render your browse/listing view (create this if you haven't yet)
        return view('merchant.profile.browse', compact('profiles'));
    }

    /**
     * Single merchant profile page: header + role-specific content.
     */
    public function show(MerchantProfile $merchantProfile)
    {
        // Delegate role-specific data preparation to the Strategy layer
        $resolver = app(\App\Domain\MerchantProfile\RoleStrategyResolver::class);
        $strategy = $resolver->for($merchantProfile);
        $data = $strategy->handle($merchantProfile);

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
