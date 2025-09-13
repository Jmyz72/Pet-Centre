# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

**Laravel Application:**
- `composer run dev` - Start development server with queue listener, logs, and Vite (uses concurrently)
- `php artisan serve` - Start Laravel development server only
- `php artisan queue:work` - Process background jobs
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh --seed` - Fresh migration with seeders

**Frontend Assets:**
- `npm run dev` - Start Vite development server for asset compilation
- `npm run build` - Build assets for production

**Testing:**
- `composer run test` - Run PHPUnit tests (uses Pest testing framework)
- `php artisan test` - Run tests directly via Artisan
- Individual test files are located in `tests/Unit` and `tests/Feature`

**Code Quality:**
- `php artisan pint` - Format PHP code using Laravel Pint

## Architecture Overview

This is a **Laravel 12** application with **Filament 3.2** admin panel that manages a pet services marketplace with three main merchant types:

### Domain Architecture
The application uses Domain-Driven Design patterns with specialized strategies:

**Merchant Types & Roles:**
- **Clinics**: Provide veterinary services with specialized staff roles
- **Groomers**: Offer grooming packages with variations based on pet size/breed
- **Shelters**: Manage adoptable pets

**Key Domain Patterns:**
- `app/Domain/MerchantProfile/` - Role strategy pattern for different merchant types
- `app/Domain/Staff/` - Staff factory pattern with role-specific implementations
- Merchant-scoped resources using `MerchantScopedResource` trait

### Database Architecture
**Core Models:**
- `User` - Customers and merchant owners
- `MerchantProfile` - Business profiles with role-based functionality
- `Booking` - Central booking model supporting services/packages for different merchant types
- `Payment` - Payment tracking with multiple provider support

**Polymorphic Relationships:**
- Bookings can relate to either `Service` (clinics) or `Package` (groomers) or `Pet` (shelter adoptions)
- Customer pets vs merchant pets (shelter pets) distinction
- Staff can be assigned to different services/packages based on merchant role

### Filament Admin Structure
**Multi-Panel Setup:**
- Admin panel: `app/Filament/Resources/` - Global admin management
- Merchant panel: `app/Filament/Merchant/` - Merchant-specific resources with automatic scoping

**Authentication & Authorization:**
- Uses Spatie Laravel Permission for role-based access
- Merchant resources automatically scope to current merchant context
- Breeze authentication for customer-facing areas

### Key Features
**Booking System:**
- Supports different booking types: services, packages, adoptions
- Time-based scheduling with staff assignment
- Payment integration with hold/release mechanism
- Rating system using `willvincent/laravel-rateable`

**Multi-Tenant Architecture:**
- Merchant isolation through relationship scoping
- Domain-specific business logic in service classes
- Role-based UI customization in Filament panels

### File Organization
- `app/Http/Controllers/Api/` - API endpoints
- `app/Http/Requests/` - Form request validation
- `app/Services/` - Business logic services
- `resources/views/bookings/` - Customer-facing booking interface
- `database/migrations/` - Extensive migration history showing feature evolution

## Development Notes

**Framework Versions:**
- PHP 8.2+
- Laravel 12.0
- Filament 3.2
- Pest 3.8 (testing framework)
- Tailwind CSS + Alpine.js + Flowbite

**XAMPP Development:**
- Application runs in XAMPP environment
- Database migrations use SQLite for testing, likely MySQL for development
- Vite handles asset compilation with Laravel integration

**Branch Structure:**
- Currently on `Payment` branch
- Main branch is `master`
- Recent commits show active payment system development