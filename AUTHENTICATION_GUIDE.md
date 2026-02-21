# Laravel Breeze Authentication System Documentation

## Overview
This Hotel Management Information System (MIS) now includes a complete authentication system powered by **Laravel Breeze**. The system provides secure user registration, login, password reset, and email verification functionality.

---

## Features Implemented

### ២.១. Laravel Breeze Installation ✅

Laravel Breeze has been successfully installed and configured with the **Blade** stack, providing:
- Clean, minimal authentication UI
- Tailwind CSS styling
- Responsive design
- Forms for registration, login, and password management

**Installation Commands Used:**
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
npm install && npm run build
```

---

### ២.២. Registration & Login System ✅

#### **Registration Features:**
- **Route:** `/register`
- **Fields:**
  - Name (required)
  - Email (required, must be unique and valid)
  - Password (required, minimum 8 characters)
  - Password Confirmation (required, must match password)
- **Validation:** All fields are validated on the server-side
- **Auto-login:** Users are automatically logged in after successful registration
- **Location:** `resources/views/auth/register.blade.php`

#### **Login Features:**
- **Route:** `/login`
- **Fields:**
  - Email (required)
  - Password (required)
  - Remember Me (optional checkbox)
- **Session Management:** Secure session handling with database driver
- **Location:** `resources/views/auth/login.blade.php`

#### **Logout:**
- **Route:** `POST /logout`
- **Method:** Secure POST request with CSRF protection
- Available from profile menu in the navigation

---

### ២.៣. Password Reset ✅

Complete password reset workflow:

#### **Step 1: Request Password Reset**
- **Route:** `/forgot-password`
- **Process:**
  1. User enters their email address
  2. System validates email exists in database
  3. Password reset link is sent to user's email
  4. Link is valid for 60 minutes

#### **Step 2: Reset Password**
- **Route:** `/reset-password/{token}`
- **Fields:**
  - Email (pre-filled from token)
  - New Password (minimum 8 characters)
  - Confirm Password
- **Security:** Tokens are signed and time-limited

#### **Email Configuration:**
For development, emails are logged to: `storage/logs/laravel.log`

For production SMTP setup, update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourhotel.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

### ២.៤. Email Verification ✅

Email verification is now **required** for all users before accessing protected admin areas.

#### **How It Works:**
1. **Upon Registration:**
   - User completes registration form
   - Account is created but marked as "unverified"
   - Verification email is automatically sent

2. **Verification Email:**
   - Contains a signed, time-limited verification link
   - Link format: `/verify-email/{id}/{hash}`
   - Link expires after set time for security

3. **Accessing Protected Routes:**
   - Users attempting to access `/admin/*` routes are redirected to verification notice
   - They must verify their email before proceeding

4. **Verification Process:**
   - User clicks verification link in email
   - Email is marked as verified in database
   - User is redirected to dashboard with success message

5. **Resend Verification Email:**
   - Available on verification notice page
   - Throttled to prevent abuse (max 6 attempts per minute)

#### **Implementation Details:**

**User Model Update:**
```php
// app/Models/User.php
class User extends Authenticatable implements MustVerifyEmail
```

**Route Protection:**
```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    // All admin routes protected here
});
```

---

## Routes Overview

### **Public Routes (Guest Only):**
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /forgot-password` - Password reset request form
- `POST /forgot-password` - Send password reset email
- `GET /reset-password/{token}` - Password reset form
- `POST /reset-password` - Process password reset

### **Protected Routes (Authenticated Users):**
- `GET /dashboard` - User dashboard
- `GET /profile` - Edit profile
- `PATCH /profile` - Update profile
- `DELETE /profile` - Delete account
- `POST /logout` - Logout user

### **Email Verification Routes (Authenticated):**
- `GET /verify-email` - Email verification notice
- `GET /verify-email/{id}/{hash}` - Verify email address
- `POST /email/verification-notification` - Resend verification email

### **Admin Routes (Authenticated + Verified):**
All routes under `/admin/*` prefix require authentication AND email verification:
- `/admin/dashboard` - Admin dashboard
- `/admin/roomtypes` - Room types management
- `/admin/amenities` - Amenities management
- `/admin/rooms` - Rooms management
- `/admin/countries` - Countries management
- `/admin/settings` - Hotel settings

---

## Security Features

### ✅ **CSRF Protection**
All forms include CSRF tokens to prevent cross-site request forgery attacks.

### ✅ **Password Hashing**
Passwords are hashed using bcrypt (12 rounds) before storage.

### ✅ **Rate Limiting**
- Login attempts: Throttled to prevent brute force attacks
- Email verification: Max 6 requests per minute
- Password reset: Max 6 requests per minute

### ✅ **Signed URLs**
Email verification links are signed to prevent tampering.

### ✅ **Session Security**
- Sessions stored in database
- Secure session cookies
- Auto-logout after 120 minutes of inactivity

### ✅ **Remember Me**
Secure "remember me" functionality with encrypted cookies.

---

## Database Tables

### **users**
```
- id
- name
- email (unique)
- email_verified_at (nullable)
- password (hashed)
- remember_token
- created_at
- updated_at
```

### **password_reset_tokens**
```
- email (primary)
- token
- created_at
```

### **sessions**
```
- id (primary)
- user_id (nullable, foreign key)
- ip_address
- user_agent
- payload
- last_activity
```

---

## Testing the System

### **1. Register a New User:**
```
1. Visit http://localhost:8000/register
2. Fill in name, email, password
3. Click "Register"
4. Check storage/logs/laravel.log for verification email
```

### **2. Email Verification:**
```
1. Open storage/logs/laravel.log
2. Find the verification URL in the email log
3. Copy and paste the URL in browser
4. Email should be verified
```

### **3. Login:**
```
1. Visit http://localhost:8000/login
2. Enter email and password
3. Check "Remember Me" if desired
4. Click "Log in"
```

### **4. Password Reset:**
```
1. Visit http://localhost:8000/forgot-password
2. Enter your email
3. Check storage/logs/laravel.log for reset link
4. Click the link and set new password
```

### **5. Access Admin Area:**
```
1. After login and email verification
2. Visit http://localhost:8000/admin/dashboard
3. You should have access to all admin features
```

---

## User Interface Files

### **Blade Templates:**
- `resources/views/auth/register.blade.php` - Registration form
- `resources/views/auth/login.blade.php` - Login form
- `resources/views/auth/forgot-password.blade.php` - Password reset request
- `resources/views/auth/reset-password.blade.php` - Password reset form
- `resources/views/auth/verify-email.blade.php` - Email verification notice
- `resources/views/auth/confirm-password.blade.php` - Password confirmation
- `resources/views/profile/edit.blade.php` - User profile editor

### **Controllers:**
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Controllers/Auth/EmailVerificationPromptController.php`
- `app/Http/Controllers/Auth/VerifyEmailController.php`
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`
- `app/Http/Controllers/ProfileController.php`

---

## Configuration Files

### **.env Settings:**
```env
# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Email Configuration (Development)
MAIL_MAILER=log

# For Production SMTP:
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=your-email@gmail.com
# MAIL_PASSWORD=your-app-password
# MAIL_ENCRYPTION=tls
```

---

## Middleware Used

1. **auth** - Ensures user is authenticated
2. **verified** - Ensures user has verified their email
3. **guest** - Only allows non-authenticated users
4. **throttle** - Rate limiting
5. **signed** - Validates signed URLs

---

## Next Steps / Enhancements

### **Suggested Improvements:**

1. **Role-Based Access Control (RBAC):**
   - Add admin/user roles
   - Restrict admin panel to admin users only
   - Install Spatie Laravel Permission package

2. **Two-Factor Authentication (2FA):**
   - Add optional 2FA for extra security
   - Use Laravel Fortify or similar package

3. **Social Login:**
   - Add "Login with Google/Facebook" options
   - Use Laravel Socialite

4. **Email Templates:**
   - Customize email templates with hotel branding
   - Add hotel logo and styling

5. **User Management:**
   - Create admin interface to manage users
   - View all registered users
   - Activate/deactivate accounts

6. **Production Email Setup:**
   - Configure SMTP for production
   - Use services like SendGrid, Mailgun, or AWS SES
   - Set up proper SPF/DKIM records

---

## Troubleshooting

### **Can't receive verification emails?**
- Check `storage/logs/laravel.log` for logged emails during development
- Verify `MAIL_MAILER=log` is set in `.env`
- Ensure queue worker is running if using queued emails

### **Session timeout issues?**
- Increase `SESSION_LIFETIME` in `.env` (default is 120 minutes)
- Clear sessions: `php artisan session:table` then `php artisan migrate:refresh`

### **Can't access admin routes after login?**
- Verify email address is marked as verified in database
- Check `users` table, `email_verified_at` column should not be null
- Manually verify: Update user record in database

### **Password reset link expired?**
- Links expire after 60 minutes by default
- Request a new password reset link
- Configure timeout in `config/auth.php`

---

## Summary

✅ **Laravel Breeze Installed** - Complete authentication scaffolding  
✅ **Registration System** - Secure user registration with validation  
✅ **Login System** - Secure login with remember me functionality  
✅ **Password Reset** - Complete forgot password workflow  
✅ **Email Verification** - Required email verification for admin access  
✅ **Security Features** - CSRF, rate limiting, password hashing, signed URLs  
✅ **Protected Routes** - All admin routes require authentication + verification  

The authentication system is now **production-ready** and follows Laravel best practices!

---

**Date Implemented:** January 20, 2026  
**Laravel Version:** 11.x  
**Breeze Version:** 2.3  
**Stack:** Blade + Tailwind CSS
