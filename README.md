# Pet Centre - Laravel Application

A comprehensive pet service management platform built with Laravel 12, Filament admin panel, and modern web technologies.

## Overview

Pet Centre is a web application that connects pet owners with service providers including veterinary clinics, pet shelters, and grooming services. The platform allows merchants to register, manage their profiles, and offer packages and services to pet owners.

## Features

### For Administrators
- **Admin Dashboard** - Comprehensive admin panel built with Filament
- **Merchant Application Management** - Review and approve/reject merchant applications
- **User Management** - Manage platform users and roles
- **System Configuration** - Manage pet types, breeds, sizes, and service categories

### For Merchants
- **Application System** - Apply to become a verified merchant (clinic, shelter, or groomer)
- **Profile Management** - Maintain business information and operating hours
- **Package Management** - Create and manage service packages with variations
- **Service Offerings** - Define available services
- **Pet Management** - Register pets available for adoption (shelters)
- **Rating System** - Receive and manage customer reviews

### For Pet Owners
- **Merchant Discovery** - Browse verified service providers
- **Service Booking** - Access merchant services and packages
- **Reviews & Ratings** - Rate and review service experiences
- **Notifications** - Stay updated on application status and activities

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Admin Panel**: Filament 3.x
- **Frontend**: TailwindCSS, Alpine.js, Flowbite
- **Build Tool**: Vite
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **Testing**: Pest PHP
- **Code Quality**: Laravel Pint

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL or SQLite database

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Jmyz72/Pet-Centre.git
   cd Pet-Centre
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pet_centre
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## Development

### Running in Development Mode

For active development with hot reloading:

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server  
npm run dev
```

### Code Quality

Format code using Laravel Pint:
```bash
./vendor/bin/pint
```

Run tests:
```bash
./vendor/bin/pest
```

### Database Management

Run migrations:
```bash
php artisan migrate
```

Fresh migration with seeding:
```bash
php artisan migrate:fresh --seed
```

## Admin Panel Access

The Filament admin panel is available at `/admin`. To create an admin user:

```bash
php artisan make:filament-user
```

## Project Structure

```
app/
├── Filament/           # Filament admin resources
│   ├── Merchant/       # Merchant panel resources
│   └── Resources/      # Admin panel resources
├── Http/Controllers/   # Application controllers
├── Models/            # Eloquent models
└── Notifications/     # Custom notifications

database/
├── migrations/        # Database schema migrations
└── seeders/          # Database seeders

resources/
├── views/            # Blade templates
├── css/              # Stylesheets
└── js/               # JavaScript files
```

## API Documentation

### Public Routes
- `GET /` - Homepage
- `GET /merchants` - Browse merchants
- `GET /merchants/{id}` - View merchant profile

### Authenticated Routes
- `GET /profile` - User profile management
- `GET /apply-merchant` - Merchant application
- `GET /notifications` - User notifications

### Admin Routes (Filament)
- `/admin` - Admin dashboard
- `/admin/users` - User management
- `/admin/merchant-applications` - Application management

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and ensure code quality
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting
- Write tests for new features
- Update documentation as needed

## Security

- Sensitive configuration should never be committed to version control
- Use proper validation and authorization
- Follow Laravel security best practices
- Report security vulnerabilities privately

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.

---

Built with ❤️ using Laravel and Filament
