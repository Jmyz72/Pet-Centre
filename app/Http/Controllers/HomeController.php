<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Service;
use App\Models\Package;
use App\Models\Booking;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home/welcome page with dynamic pet data
     */
    public function index()
    {
        // Get 4 random available pets for adoption with enhanced randomization
        $featuredPets = Pet::with(['petType', 'petBreed', 'size', 'merchantProfile'])
            ->where('status', Pet::STATUS_AVAILABLE)
            ->whereHas('merchantProfile', function($query) {
                // Only include pets from merchants with complete profiles
                $query->whereNotNull('name')
                      ->whereNotNull('phone');
            })
            ->inRandomOrder(time()) // Use current timestamp as seed for better randomization
            ->limit(4)
            ->get()
            ->map(function ($pet) {
                return [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'type' => $pet->petType->name ?? 'Unknown',
                    'breed' => $pet->petBreed->name ?? null,
                    'age' => $this->formatAgeAsInteger($pet->date_of_birth),
                    'image' => $pet->image ? asset('storage/' . $pet->image) : asset('images/placeholder-pet.png'),
                    'description' => $pet->description,
                    'adoption_fee' => $pet->adoption_fee,
                    'sex' => ucfirst($pet->sex),
                    'size' => $pet->size->label ?? null,
                    'vaccinated' => $pet->vaccinated,
                    'merchant_id' => $pet->merchant_id,
                    'merchant_name' => $pet->merchantProfile->name ?? 'Pet Shelter',
                ];
            });

        // Get 6 featured services from active merchants with randomization
        $featuredServices = Service::with(['serviceType', 'merchantProfile'])
            ->where('is_active', true)
            ->whereHas('merchantProfile', function($query) {
                // Only include services from merchants with complete profiles
                $query->whereNotNull('name')
                      ->whereNotNull('phone');
            })
            ->inRandomOrder(time() + 1) // Different seed for services
            ->limit(6)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                    'duration' => $service->duration_minutes,
                    'type' => $service->serviceType->name ?? 'General',
                    'category' => $service->serviceType->name ?? 'Veterinary Service',
                    'merchant_name' => $service->merchantProfile->name ?? 'Pet Clinic',
                    'merchant_id' => $service->merchant_id,
                ];
            });

        // Get 6 featured packages from active merchants with randomization
        $featuredPackages = Package::with(['packageTypes', 'merchantProfile'])
            ->where('is_active', true)
            ->whereHas('merchantProfile', function($query) {
                // Only include packages from merchants with complete profiles
                $query->whereNotNull('name')
                      ->whereNotNull('phone');
            })
            ->inRandomOrder(time() + 2) // Different seed for packages
            ->limit(6)
            ->get()
            ->map(function ($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'duration' => $package->duration_minutes,
                    'types' => $package->packageTypes->pluck('name')->toArray(),
                    'merchant_name' => $package->merchantProfile->name ?? 'Pet Groomer',
                    'merchant_id' => $package->merchant_id,
                ];
            });

        // Get real statistics from database
        $stats = [
            'adoptions' => Booking::where('booking_type', 'adoption')
                ->where('status', 'confirmed')
                ->count(),
            'clinics' => MerchantProfile::where('role', 'clinic')
                ->whereNotNull('name')
                ->whereNotNull('phone')
                ->count(),
            'services' => Service::where('is_active', true)
                ->whereHas('merchantProfile', function($query) {
                    $query->whereNotNull('name')->whereNotNull('phone');
                })
                ->count(),
            'happy_families' => Booking::where('booking_type', 'adoption')
                ->where('status', 'confirmed')
                ->distinct('customer_id')
                ->count('customer_id')
        ];

        return view('welcome', compact('featuredPets', 'featuredServices', 'featuredPackages', 'stats'));
    }

    /**
     * Format pet age as integer (in months)
     */
    private function formatAgeAsInteger($dateOfBirth)
    {
        if (!$dateOfBirth) {
            return 'Unknown';
        }

        $now = now();
        $birth = \Carbon\Carbon::parse($dateOfBirth);
        
        $totalMonths = $birth->diffInMonths($now);
        
        if ($totalMonths < 1) {
            return '< 1 month';
        } elseif ($totalMonths < 12) {
            return $totalMonths . ' months';
        } else {
            $years = floor($totalMonths / 12);
            $remainingMonths = $totalMonths % 12;
            
            if ($remainingMonths > 0) {
                return $years . 'y ' . $remainingMonths . 'm';
            } else {
                return $years . ' years';
            }
        }
    }
}
