# Database Seeding Guide

## Overview
This project includes comprehensive database seeders that populate the database with demo data for testing and development.

## Quick Start

To seed the database with all demo data:

```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Or seed only (without migrating)
php artisan db:seed
```

## Seeding Order

The seeders run in the following order to respect database dependencies:

1. **RolesAndPermissionsSeeder** - Creates roles (admin, staff, guest) and 32 permissions
2. **UserSeeder** - Creates 8 demo users (3 admins, 3 staff, 2 guests)
3. **RoomTypeSeeder** - Creates 10 room types
4. **AmenitySeeder** - Creates 5 amenities (Wi-Fi, TV, etc.)
5. **RoomSeeder** - Creates 8 rooms with amenities
6. **SettingSeeder** - Creates 19 system settings
7. **HotelSettingsSeeder** - Adds hotel-specific settings
8. **PageSeeder** - Creates 4 static pages (About, Contact, Privacy, Terms)
9. **ContentSeeder** - Creates blog posts, services, and slides
10. **GuestSeeder** - Creates 25 guest profiles with demo data

## Demo Credentials

### Admin Accounts (Full Access)
- **Main Admin**: admin@hotel.com / password123
- **John Administrator**: john.admin@hotel.com / password123
- **Sarah Manager**: sarah.manager@hotel.com / password123

### Staff Accounts (Limited Access)
- **Staff User**: staff@hotel.com / password123
- **David Receptionist**: david.staff@hotel.com / password123
- **Lisa Frontdesk**: lisa.staff@hotel.com / password123

### Guest Accounts (Self-Service Portal)
- **Guest User**: guest@hotel.com / password123
- **Test User**: test@hotel.com / password123
- Plus 25 additional demo guest accounts

## Seeded Data Summary

| Resource | Count | Description |
|----------|-------|-------------|
| **Users** | 33 | 3 admins, 3 staff, 27 guests |
| **Roles** | 3 | admin, staff, guest |
| **Permissions** | 32 | Granular permissions for all features |
| **Room Types** | 10 | Various room categories |
| **Rooms** | 8 | Physical rooms with amenities |
| **Amenities** | 5 | Room amenities |
| **Pages** | 4 | Static CMS pages |
| **Blog Posts** | 15 | Sample blog content |
| **Blog Categories** | 5 | Travel Tips, Hotel News, etc. |
| **Services** | 6 | Hotel services (Wi-Fi, Pool, Spa, etc.) |
| **Slides** | 3 | Homepage hero slides |
| **Guest Profiles** | 25 | Complete guest information |
| **Settings** | 19 | System configuration |

## Permission Structure

### Admin Permissions (32 total)
- Dashboard: view-dashboard
- Bookings: view, create, edit, delete, check-in, check-out
- Rooms: view, create, edit, delete, manage-types
- Guests: view, create, edit, delete
- Users: view, create, edit, delete, manage-users
- Roles: view-roles, manage-roles
- Reviews: view, manage
- Settings: view, manage
- Reports: view, export
- Amenities: manage
- Countries: manage

### Staff Permissions (12 total)
- Dashboard access
- Booking management (view, create, edit, check-in/out)
- View rooms and room types
- Guest management (view, create, edit)
- View reviews

### Guest Permissions (3 total)
- View own bookings
- Manage own profile
- Submit reviews

## Individual Seeders

### Run Specific Seeders

```bash
# Roles and permissions only
php artisan db:seed --class=RolesAndPermissionsSeeder

# Users only
php artisan db:seed --class=UserSeeder

# Content (blog, services, slides)
php artisan db:seed --class=ContentSeeder

# Guest profiles
php artisan db:seed --class=GuestSeeder

# All settings
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=HotelSettingsSeeder
```

## Customization

### Adding More Demo Users

Edit `database/seeders/UserSeeder.php` and add new user entries:

```php
User::create([
    'name' => 'Your Name',
    'email' => 'your@email.com',
    'password' => bcrypt('password123'),
    'role' => 'admin', // or 'staff', 'guest'
    'email_verified_at' => now(),
]);
```

### Modifying Permissions

Edit `database/seeders/RolesAndPermissionsSeeder.php` to add/remove permissions or change role assignments.

### Adjusting Demo Data

- **Rooms**: Edit `RoomSeeder.php` to change room count or properties
- **Blog Posts**: Modify `ContentSeeder.php` to adjust blog content
- **Guest Profiles**: Update `GuestSeeder.php` to change guest data

## Troubleshooting

### Foreign Key Constraint Errors
Ensure seeders run in the correct order. The `DatabaseSeeder.php` handles this automatically.

### Duplicate Entry Errors
Seeders use `updateOrInsert` or `firstOrCreate` to handle re-seeding safely.

### Permission Denied (403 Errors)
After seeding, clear the cache:
```bash
php artisan cache:clear
php artisan config:clear
```

### Missing Data After Seeding
Check if all seeders are called in `DatabaseSeeder.php`:
```php
$this->call([
    RolesAndPermissionsSeeder::class,
    UserSeeder::class,
    // ... all other seeders
]);
```

## Production Considerations

⚠️ **Important**: These seeders include demo data and should NOT be run in production.

For production, create a separate seeder:

```bash
php artisan make:seeder ProductionSeeder
```

Include only:
- Essential roles and permissions
- One admin user (with secure password)
- Required settings
- No demo/test data

## Testing

After seeding, verify data:

```bash
# Count records
php artisan tinker
>>> \App\Models\User::count()
>>> \App\Models\Role::withCount('permissions')->get()
>>> DB::table('rooms')->count()
```

## Related Files

- `database/seeders/DatabaseSeeder.php` - Main seeder orchestrator
- `database/seeders/RolesAndPermissionsSeeder.php` - RBAC setup
- `database/seeders/UserSeeder.php` - Demo users
- `database/seeders/ContentSeeder.php` - Blog, services, slides
- `database/seeders/GuestSeeder.php` - Guest profiles and reviews
- `database/seeders/RoomSeeder.php` - Rooms and room types
- `database/seeders/SettingSeeder.php` - System settings

## Notes

- All user passwords are `password123` (for demo purposes only)
- Guest profiles include realistic data (names, nationalities, etc.)
- Blog posts are randomly published/unpublished
- Rooms are automatically linked to amenities
- Settings include default values for all system features
