# 🔐 Role-Based Access Control (RBAC) Implementation

## Overview

Comprehensive Role-Based Access Control system with **3 user roles**: Admin, Staff, and Guest.

---

## 1. User Roles

### Role Hierarchy:

```
┌─────────────────────────────────────────┐
│  ADMIN (Highest Permission)             │
│  - Full system access                   │
│  - Manage all resources                 │
│  - Manage settings                      │
└─────────────────────────────────────────┘
            ↓
┌─────────────────────────────────────────┐
│  STAFF (Medium Permission)              │
│  - Manage rooms, bookings               │
│  - View reports                         │
│  - Limited access                       │
└─────────────────────────────────────────┘
            ↓
┌─────────────────────────────────────────┐
│  GUEST (Lowest Permission)              │
│  - View own profile                     │
│  - Make bookings (future)               │
│  - Limited read-only access             │
└─────────────────────────────────────────┘
```

---

## 2. Database Schema

### Migration:
**File:** `database/migrations/2026_01_20_135351_add_role_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'staff', 'guest'])
          ->default('guest')
          ->after('email');
});
```

### Users Table Structure:
```sql
users
├── id
├── name
├── email
├── role (enum: 'admin', 'staff', 'guest')  ← NEW
├── profile_image
├── email_verified_at
├── password
├── remember_token
├── created_at
└── updated_at
```

---

## 3. User Model Methods

**File:** `app/Models/User.php`

### Helper Methods:

```php
// Check if user is admin
$user->isAdmin()  // Returns true/false

// Check if user is staff
$user->isStaff()  // Returns true/false

// Check if user is guest
$user->isGuest()  // Returns true/false

// Check specific role
$user->hasRole('admin')  // Returns true/false

// Check multiple roles
$user->hasAnyRole(['admin', 'staff'])  // Returns true/false
```

### Usage Example:
```php
if (Auth::user()->isAdmin()) {
    // Admin-only code
}

if (Auth::user()->hasAnyRole(['admin', 'staff'])) {
    // Staff and Admin code
}
```

---

## 4. Middleware Protection

### CheckRole Middleware:
**File:** `app/Http/Middleware/CheckRole.php`

```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!$request->user()) {
        return redirect()->route('login');
    }

    if (!$request->user()->hasAnyRole($roles)) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
```

### Middleware Registration:
**File:** `bootstrap/app.php`

```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
]);
```

---

## 5. Routes Protection

**File:** `routes/web.php`

### All Admin Routes (Admin + Staff):
```php
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin,staff'])
    ->group(function () {
        // Accessible by Admin AND Staff
        Route::get('/dashboard', ...);
        Route::resource('roomtypes', ...);
        Route::resource('amenities', ...);
        Route::resource('rooms', ...);
    });
```

### Admin-Only Routes:
```php
Route::middleware('role:admin')->group(function () {
    // Only accessible by Admin
    Route::get('/settings', ...);
    Route::put('/settings', ...);
});
```

---

## 6. Gates and Policies

**File:** `app/Providers/AppServiceProvider.php`

### Defined Gates:

| Gate Name | Allowed Roles | Purpose |
|-----------|---------------|---------|
| `admin-only` | Admin | Admin exclusive actions |
| `staff-access` | Admin, Staff | Staff-level access |
| `manage-users` | Admin | User management |
| `manage-settings` | Admin | System settings |
| `manage-rooms` | Admin, Staff | Room management |
| `view-reports` | Admin, Staff | View reports |

### Gate Usage in Controllers:
```php
// Check authorization
if (Gate::allows('admin-only')) {
    // User is admin
}

// Or throw 403 if unauthorized
Gate::authorize('manage-settings');

// In Blade views
@can('admin-only')
    <button>Admin Button</button>
@endcan
```

### Gate Usage in Blade:
```blade
@can('manage-users')
    <a href="{{ route('admin.users') }}">Manage Users</a>
@endcan

@cannot('admin-only')
    <p>You don't have admin access</p>
@endcannot
```

---

## 7. Test User Accounts

### Seeded Users:

| Email | Password | Role | Access Level |
|-------|----------|------|--------------|
| admin@hotel.com | password123 | admin | Full access |
| staff@hotel.com | password123 | staff | Limited admin access |
| guest@hotel.com | password123 | guest | Profile only |
| test@hotel.com | password123 | guest | Profile only |

---

## 8. Access Control Matrix

### Route Access by Role:

| Route | Admin | Staff | Guest |
|-------|:-----:|:-----:|:-----:|
| `/admin/dashboard` | ✅ | ✅ | ❌ |
| `/admin/roomtypes` | ✅ | ✅ | ❌ |
| `/admin/amenities` | ✅ | ✅ | ❌ |
| `/admin/rooms` | ✅ | ✅ | ❌ |
| `/admin/settings` | ✅ | ❌ | ❌ |
| `/profile` | ✅ | ✅ | ✅ |
| `/dashboard` | ✅ | ✅ | ✅ |

---

## 9. Testing RBAC

### Test Admin Access:
```bash
1. Login as admin@hotel.com
2. Visit /admin/dashboard → ✅ Access granted
3. Visit /admin/settings → ✅ Access granted
4. All admin features visible
```

### Test Staff Access:
```bash
1. Login as staff@hotel.com
2. Visit /admin/dashboard → ✅ Access granted
3. Visit /admin/rooms → ✅ Access granted
4. Visit /admin/settings → ❌ 403 Forbidden
```

### Test Guest Access:
```bash
1. Login as guest@hotel.com
2. Visit /admin/dashboard → ❌ 403 Forbidden
3. Visit /profile → ✅ Access granted
4. Limited to profile management
```

---

## 10. Protecting New Routes

### Example: Add Admin-Only Route
```php
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/reports', [ReportController::class, 'index'])
         ->name('admin.reports');
});
```

### Example: Add Staff+Admin Route
```php
Route::middleware('role:admin,staff')->group(function () {
    Route::get('/admin/bookings', [BookingController::class, 'index'])
         ->name('admin.bookings');
});
```

---

## 11. Controller Authorization

### Method 1: Using Middleware in Controller:
```php
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
}
```

### Method 2: Using Gates:
```php
public function destroy(User $user)
{
    Gate::authorize('manage-users');
    
    $user->delete();
    return redirect()->back();
}
```

### Method 3: Manual Check:
```php
public function index()
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }
    
    // Admin code here
}
```

---

## 12. Blade Directives

### Show/Hide Based on Role:
```blade
{{-- Show only to admin --}}
@if(Auth::user()->isAdmin())
    <button>Delete All</button>
@endif

{{-- Show to admin and staff --}}
@if(Auth::user()->hasAnyRole(['admin', 'staff']))
    <a href="{{ route('admin.rooms') }}">Manage Rooms</a>
@endif

{{-- Using Gates --}}
@can('manage-settings')
    <a href="{{ route('admin.settings') }}">Settings</a>
@endcan
```

---

## 13. Error Handling

### 403 Forbidden Page:
When unauthorized user tries to access protected route:

```
HTTP 403 - Forbidden
Unauthorized action. You do not have permission to access this page.
```

### Customizing Error Page:
Create: `resources/views/errors/403.blade.php`

---

## 14. Security Best Practices

### ✅ Always Check Authorization:
```php
// In controllers
Gate::authorize('admin-only');

// In routes
->middleware('role:admin')

// In views
@can('manage-users')
```

### ✅ Default to Least Privilege:
- New users default to 'guest' role
- Explicitly grant higher permissions
- Never assume user has access

### ✅ Validate on Backend:
- Don't rely only on frontend hiding
- Always check permissions in controller
- Use middleware consistently

---

## 15. Future Enhancements

### Planned Features:
1. **Permission System**
   - Fine-grained permissions
   - Custom permission assignments

2. **Role Management UI**
   - Admin can change user roles
   - Bulk role assignment

3. **Activity Logging**
   - Track who did what
   - Audit trail for security

4. **Multiple Roles per User**
   - User can have multiple roles
   - More flexible permissions

---

## 16. Quick Reference

### Change User Role (Tinker):
```php
php artisan tinker

$user = User::find(1);
$user->role = 'admin';
$user->save();
```

### Check Current User Role:
```php
Auth::user()->role  // Returns: 'admin', 'staff', or 'guest'
```

### Route Protection Template:
```php
Route::middleware('role:admin,staff')->group(function () {
    // Your routes here
});
```

---

## Summary

✅ **3 Roles Implemented:** Admin, Staff, Guest  
✅ **Middleware Protection:** CheckRole middleware  
✅ **Gates Defined:** 6 authorization gates  
✅ **Routes Protected:** All admin routes secured  
✅ **Helper Methods:** isAdmin(), isStaff(), isGuest()  
✅ **Test Accounts:** Created with different roles  
✅ **Documentation:** Complete RBAC guide  

**Status:** ✅ **RBAC FULLY OPERATIONAL!**

---

## Contact & Support

For questions about RBAC implementation, refer to this document or check:
- Laravel Authorization Docs
- Gates and Policies Guide  
- Middleware Documentation
