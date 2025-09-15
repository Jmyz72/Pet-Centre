<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\MerchantProfile;
use App\Models\OperatingHour;
use App\Models\Staff;
use App\Models\StaffOperatingHour;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Package;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class SetupProgressWidget extends Widget
{
    protected static string $view = 'filament.merchant.widgets.setup-progress-widget';

    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = -10; // Very high priority to appear at top

    public function getSetupSteps(): Collection
    {
        $user = auth()->user();
        $merchantProfile = $user->merchantProfile;
        
        if (!$merchantProfile) {
            return collect();
        }

        $role = $merchantProfile->role;
        $steps = collect();

        // Step 1: Profile Setup (Universal)
        $steps->push([
            'id' => 'profile',
            'title' => 'Complete Business Profile',
            'description' => 'Add description, photo, and complete your business information',
            'completed' => $this->isProfileComplete($merchantProfile),
            'url' => '/merchant/my-profile',
            'icon' => 'heroicon-o-building-storefront',
        ]);

        // Step 2: Shop Operating Hours (Universal)
        $steps->push([
            'id' => 'operating_hours',
            'title' => 'Set Shop Operating Hours',
            'description' => 'Configure when your business is open',
            'completed' => $this->hasOperatingHours($merchantProfile),
            'url' => '/merchant/operating-hours',
            'icon' => 'heroicon-o-clock',
        ]);

        // Type-specific business setup steps
        switch ($merchantProfile->role) {
            case 'clinic':
                // Step 3: Staff Management
                $steps->push([
                    'id' => 'staff',
                    'title' => 'Add Staff Members',
                    'description' => $this->getStaffDescription($merchantProfile->role),
                    'completed' => $this->hasStaff($merchantProfile),
                    'url' => '/merchant/staff',
                    'icon' => 'heroicon-o-user-group',
                ]);

                // Step 4: Staff Operating Hours
                $steps->push([
                    'id' => 'staff_hours',
                    'title' => 'Set Staff Operating Hours',
                    'description' => 'Configure when your staff members are available',
                    'completed' => $this->hasStaffOperatingHours($merchantProfile),
                    'url' => '/merchant/staff-operating-hours',
                    'icon' => 'heroicon-o-calendar-days',
                ]);

                // Step 5: Medical Services
                $steps->push([
                    'id' => 'services',
                    'title' => 'Setup Medical Services',
                    'description' => 'Add medical services your clinic offers',
                    'completed' => $this->hasServices($merchantProfile),
                    'url' => '/merchant/services',
                    'icon' => 'heroicon-o-heart',
                ]);
                break;

            case 'shelter':
                // Step 3: Pets for Adoption (no staff needed for shelters)
                $steps->push([
                    'id' => 'pets',
                    'title' => 'Add Pets for Adoption',
                    'description' => 'List pets available for adoption',
                    'completed' => $this->hasPets($merchantProfile),
                    'url' => '/merchant/pets',
                    'icon' => 'heroicon-o-heart',
                ]);
                break;

            case 'groomer':
                // Step 3: Staff Management
                $steps->push([
                    'id' => 'staff',
                    'title' => 'Add Staff Members',
                    'description' => $this->getStaffDescription($merchantProfile->role),
                    'completed' => $this->hasStaff($merchantProfile),
                    'url' => '/merchant/staff',
                    'icon' => 'heroicon-o-user-group',
                ]);

                // Step 4: Staff Operating Hours
                $steps->push([
                    'id' => 'staff_hours',
                    'title' => 'Set Staff Operating Hours',
                    'description' => 'Configure when your staff members are available',
                    'completed' => $this->hasStaffOperatingHours($merchantProfile),
                    'url' => '/merchant/staff-operating-hours',
                    'icon' => 'heroicon-o-calendar-days',
                ]);

                // Step 6: Grooming Packages
                $steps->push([
                    'id' => 'packages',
                    'title' => 'Create Service Packages',
                    'description' => 'Bundle grooming services into attractive packages',
                    'completed' => $this->hasPackages($merchantProfile),
                    'url' => '/merchant/packages',
                    'icon' => 'heroicon-o-gift',
                ]);
                break;
        }

        return $steps;
    }

    public function getCompletionPercentage(): int
    {
        $steps = $this->getSetupSteps();
        if ($steps->isEmpty()) {
            return 0;
        }

        $completedSteps = $steps->where('completed', true)->count();
        return round(($completedSteps / $steps->count()) * 100);
    }

    private function isProfileComplete(MerchantProfile $profile): bool
    {
        return !empty($profile->description) 
            && !empty($profile->photo)
            && !empty($profile->name)
            && !empty($profile->phone)
            && !empty($profile->address);
    }

    private function getStaffDescription(string $type): string
    {
        return match($type) {
            'clinic' => 'Add veterinarians and medical staff to your clinic',
            'shelter' => 'Add caretakers and volunteers to your shelter',
            'groomer' => 'Add groomers and staff to your grooming business',
            default => 'Add staff members to your business'
        };
    }

    private function hasOperatingHours(MerchantProfile $profile): bool
    {
        return OperatingHour::where('merchant_profile_id', $profile->id)->exists();
    }

    private function hasStaff(MerchantProfile $profile): bool
    {
        return Staff::where('merchant_id', $profile->id)->where('status', 'active')->exists();
    }

    private function hasStaffOperatingHours(MerchantProfile $profile): bool
    {
        $staffIds = Staff::where('merchant_id', $profile->id)->pluck('id');
        return StaffOperatingHour::whereIn('staff_id', $staffIds)->exists();
    }

    private function hasServices(MerchantProfile $profile): bool
    {
        return Service::where('merchant_id', $profile->id)->where('is_active', true)->exists();
    }

    private function hasPets(MerchantProfile $profile): bool
    {
        return Pet::where('merchant_id', $profile->id)->where('status', 'available')->exists();
    }

    private function hasPackages(MerchantProfile $profile): bool
    {
        return Package::where('merchant_id', $profile->id)->where('is_active', true)->exists();
    }
}
