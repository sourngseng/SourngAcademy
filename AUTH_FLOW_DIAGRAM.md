# 🎯 Authentication System - Visual Flow Diagram

## User Journey Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    NEW USER REGISTRATION                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  /register       │
                    │  Sign Up Form    │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Form Validation │
                    │  - Name          │
                    │  - Email (unique)│
                    │  - Password (8+) │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Create Account  │
                    │  Hash Password   │
                    │  Store in DB     │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Send Verify     │
                    │  Email (logged)  │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Auto Login      │
                    │  /dashboard      │
                    └──────────────────┘
```

---

## Login & Access Flow

```
┌─────────────────────────────────────────────────────────────┐
│                      EXISTING USER LOGIN                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  /login          │
                    │  Login Form      │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Validate Creds  │
                    │  - Check Email   │
                    │  - Verify Pass   │
                    └──────────────────┘
                              │
                    ┌─────────┴─────────┐
                    │                   │
              ✅ Valid            ❌ Invalid
                    │                   │
                    ▼                   ▼
          ┌──────────────────┐  ┌──────────────┐
          │  Create Session  │  │  Show Error  │
          │  Generate Token  │  │  Try Again   │
          └──────────────────┘  └──────────────┘
                    │
                    ▼
          ┌──────────────────┐
          │  Check Email     │
          │  Verification    │
          └──────────────────┘
                    │
          ┌─────────┴─────────┐
          │                   │
    ✅ Verified      ❌ Not Verified
          │                   │
          ▼                   ▼
┌──────────────────┐  ┌──────────────────┐
│  Grant Access    │  │  /verify-email   │
│  to Admin Panel  │  │  Please Verify   │
└──────────────────┘  └──────────────────┘
```

---

## Password Reset Flow

```
┌─────────────────────────────────────────────────────────────┐
│                     FORGOT PASSWORD                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  /forgot-password│
                    │  Enter Email     │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Check Email     │
                    │  in Database     │
                    └──────────────────┘
                              │
              ┌───────────────┴───────────────┐
              │                               │
        ✅ Found                        ❌ Not Found
              │                               │
              ▼                               ▼
    ┌──────────────────┐            ┌──────────────┐
    │  Generate Token  │            │  Show Error  │
    │  Create Link     │            │  (security)  │
    │  Send Email      │            └──────────────┘
    └──────────────────┘
              │
              ▼
    ┌──────────────────┐
    │  User Clicks     │
    │  Reset Link      │
    │  from Email      │
    └──────────────────┘
              │
              ▼
    ┌──────────────────┐
    │  /reset-password │
    │  /{token}        │
    │  Enter New Pass  │
    └──────────────────┘
              │
              ▼
    ┌──────────────────┐
    │  Validate Token  │
    │  Check Expiry    │
    │  (60 minutes)    │
    └──────────────────┘
              │
       ┌──────┴──────┐
       │             │
   ✅ Valid     ❌ Invalid
       │             │
       ▼             ▼
┌──────────────┐  ┌─────────────┐
│  Update Pass │  │  Link       │
│  Hash & Save │  │  Expired    │
│  Auto Login  │  │  Try Again  │
└──────────────┘  └─────────────┘
```

---

## Email Verification Flow

```
┌─────────────────────────────────────────────────────────────┐
│                   EMAIL VERIFICATION                         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Register or     │
                    │  Login           │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Try Access      │
                    │  /admin/*        │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Middleware      │
                    │  Check Verified  │
                    └──────────────────┘
                              │
              ┌───────────────┴───────────────┐
              │                               │
       ✅ Verified                    ❌ Not Verified
              │                               │
              ▼                               ▼
    ┌──────────────────┐            ┌──────────────────┐
    │  Grant Access    │            │  Redirect to     │
    │  Load Page       │            │  /verify-email   │
    └──────────────────┘            └──────────────────┘
                                                │
                                                ▼
                                    ┌──────────────────┐
                                    │  Verification    │
                                    │  Notice Page     │
                                    │  - Resend Email  │
                                    └──────────────────┘
                                                │
                  ┌─────────────────────────────┴────────┐
                  │                                      │
          Click Resend                          Check Email Logs
                  │                                      │
                  ▼                                      ▼
      ┌──────────────────┐                  ┌──────────────────┐
      │  Send New        │                  │  Copy Verify     │
      │  Verification    │                  │  Link from       │
      │  Email (logged)  │                  │  laravel.log     │
      └──────────────────┘                  └──────────────────┘
                  │                                      │
                  └──────────────┬───────────────────────┘
                                 │
                                 ▼
                      ┌──────────────────┐
                      │  Click Verify    │
                      │  Link            │
                      │  /verify-email/  │
                      │  {id}/{hash}     │
                      └──────────────────┘
                                 │
                                 ▼
                      ┌──────────────────┐
                      │  Validate Link   │
                      │  - Check ID      │
                      │  - Verify Hash   │
                      │  - Check Sign    │
                      └──────────────────┘
                                 │
                        ┌────────┴────────┐
                        │                 │
                    ✅ Valid         ❌ Invalid
                        │                 │
                        ▼                 ▼
              ┌──────────────────┐  ┌──────────────┐
              │  Mark Verified   │  │  Show Error  │
              │  Set Timestamp   │  │  Link Invalid│
              │  Redirect to     │  └──────────────┘
              │  Dashboard       │
              └──────────────────┘
```

---

## Protected Routes Access

```
┌─────────────────────────────────────────────────────────────┐
│              ACCESSING ADMIN ROUTES                          │
└─────────────────────────────────────────────────────────────┘

User Request: /admin/dashboard
              │
              ▼
    ┌──────────────────┐
    │  Auth Middleware │
    │  Check Login     │
    └──────────────────┘
              │
      ┌───────┴───────┐
      │               │
  ✅ Logged In   ❌ Guest
      │               │
      ▼               ▼
┌──────────────┐  ┌─────────────┐
│  Continue    │  │  Redirect   │
│  Next Check  │  │  to /login  │
└──────────────┘  └─────────────┘
      │
      ▼
┌──────────────────┐
│  Verified        │
│  Middleware      │
│  Check Email     │
└──────────────────┘
      │
  ┌───┴───┐
  │       │
✅ Yes  ❌ No
  │       │
  ▼       ▼
┌────┐  ┌──────────────┐
│OK  │  │  Redirect    │
│    │  │  /verify-    │
│    │  │  email       │
└────┘  └──────────────┘
  │
  ▼
┌──────────────────┐
│  Load Admin      │
│  Dashboard       │
│  ✅ SUCCESS!    │
└──────────────────┘
```

---

## Middleware Stack

```
┌─────────────────────────────────────────────────────────────┐
│                    MIDDLEWARE LAYERS                         │
└─────────────────────────────────────────────────────────────┘

Request: /admin/amenities
    │
    ├─► web (group)
    │   ├─ CSRF Protection
    │   ├─ Session Management
    │   ├─ Cookie Encryption
    │   └─ Share Errors to Views
    │
    ├─► auth (middleware)
    │   ├─ Check if user logged in
    │   ├─ Check session valid
    │   └─ Redirect to /login if not
    │
    ├─► verified (middleware)
    │   ├─ Check email_verified_at
    │   └─ Redirect to /verify-email if null
    │
    └─► Controller
        └─ AmenityController@index
            └─ Return view/response
```

---

## Database Relationships

```
┌──────────────────┐
│      users       │
├──────────────────┤
│ id (PK)          │◄────┐
│ name             │     │
│ email (unique)   │     │
│ email_verified_at│     │
│ password (hash)  │     │
│ remember_token   │     │
│ timestamps       │     │
└──────────────────┘     │
                         │
                         │ Foreign Key
                         │
                    ┌────┴─────────────┐
                    │    sessions      │
                    ├──────────────────┤
                    │ id (PK)          │
                    │ user_id (FK)     │
                    │ ip_address       │
                    │ user_agent       │
                    │ payload          │
                    │ last_activity    │
                    └──────────────────┘

┌────────────────────────────┐
│  password_reset_tokens     │
├────────────────────────────┤
│ email (PK)                 │
│ token                      │
│ created_at                 │
└────────────────────────────┘
```

---

## File Structure

```
hotel_mis.com/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── EmailVerificationPromptController.php
│   │   │   │   ├── VerifyEmailController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── ConfirmablePasswordController.php
│   │   │   │   └── PasswordController.php
│   │   │   │
│   │   │   ├── ProfileController.php
│   │   │   └── [Other Admin Controllers]
│   │   │
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       └── EnsureEmailIsVerified.php
│   │
│   └── Models/
│       └── User.php (implements MustVerifyEmail)
│
├── database/
│   ├── migrations/
│   │   ├── *_create_users_table.php
│   │   ├── *_create_password_reset_tokens_table.php
│   │   └── *_create_sessions_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php
│
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── forgot-password.blade.php
│       │   ├── reset-password.blade.php
│       │   ├── verify-email.blade.php
│       │   └── confirm-password.blade.php
│       │
│       ├── profile/
│       │   └── edit.blade.php
│       │
│       ├── layouts/
│       │   ├── app.blade.php (authenticated layout)
│       │   └── guest.blade.php (guest layout)
│       │
│       └── dashboard.blade.php
│
├── routes/
│   ├── web.php (admin routes + auth require)
│   └── auth.php (authentication routes)
│
└── Documentation/
    ├── AUTHENTICATION_GUIDE.md (complete guide)
    ├── QUICK_START.md (quick reference)
    └── README_AUTH.md (implementation summary)
```

---

## Security Layers

```
┌─────────────────────────────────────────────────────────────┐
│                   SECURITY IMPLEMENTATION                    │
└─────────────────────────────────────────────────────────────┘

Layer 1: HTTPS (Production)
    │
    ▼
Layer 2: CSRF Protection
    │  └─ Token validation on all POST/PUT/DELETE
    ▼
Layer 3: Rate Limiting
    │  ├─ Login: throttle:6,1
    │  ├─ Email verify: throttle:6,1
    │  └─ Password reset: throttle:6,1
    ▼
Layer 4: Input Validation
    │  ├─ Server-side validation
    │  ├─ Type checking
    │  └─ Length restrictions
    ▼
Layer 5: Password Hashing
    │  ├─ Bcrypt algorithm
    │  ├─ 12 rounds
    │  └─ Salted hashes
    ▼
Layer 6: Signed URLs
    │  ├─ Email verification links
    │  ├─ Tamper detection
    │  └─ Expiration time
    ▼
Layer 7: Session Security
    │  ├─ Database storage
    │  ├─ Encrypted cookies
    │  ├─ IP tracking
    │  └─ User agent validation
    ▼
Layer 8: Email Verification
    └─ Required for admin access
```

---

## Quick Reference

### Test Credentials:
```
Admin:
  Email: admin@hotel.com
  Password: password123
  Status: ✅ Verified

User:
  Email: test@hotel.com
  Password: password123
  Status: ✅ Verified
```

### Key URLs:
```
Login:          /login
Register:       /register
Logout:         POST /logout (requires CSRF)
Forgot:         /forgot-password
Reset:          /reset-password/{token}
Verify Email:   /verify-email
Profile:        /profile
Dashboard:      /dashboard
Admin Panel:    /admin/dashboard
```

### Commands:
```bash
# Clear caches
php artisan optimize:clear

# Create users
php artisan db:seed --class=UserSeeder

# View routes
php artisan route:list

# Check logs
tail -f storage/logs/laravel.log

# Tinker (test)
php artisan tinker
>>> User::where('email', 'admin@hotel.com')->first()
```

---

**Ready to use! 🚀**

Login at: http://localhost:8000/login

