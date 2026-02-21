# Dynamic Logo Configuration Guide

## Overview
All user role panels (Admin, Staff, Guest) now dynamically load logos and hotel names from the Settings page, providing a unified branding experience across the entire system.

## Features

### ✅ Dynamic Logo Loading
- Logo automatically loads from Settings → Logo & Favicon
- Falls back to default AdminLTE logo if no custom logo uploaded
- Consistent across all portals (Admin, Staff, Guest)

### ✅ Dynamic Hotel Name
- Hotel name displays from Settings → General Settings → Hotel Name
- Admin portal: Shows hotel name as-is
- Staff portal: Shows "Hotel Name - Staff"
- Guest portal: Shows "Hotel Name - Guest"

### ✅ Image Optimization
- Logos automatically optimized with:
  - `img-circle` class for circular display
  - `max-height: 33px` for consistent sizing
  - `object-fit: cover` to prevent distortion
  - `elevation-3` for subtle shadow effect

## How to Update Logo

### Step 1: Access Settings
1. Log in as Admin
2. Navigate to: **Settings** (gear icon in sidebar)
3. Click on **Logo & Favicon** tab

### Step 2: Upload Logo
1. Click **Choose File** under "Hotel Logo"
2. Select your logo image (JPEG, PNG, GIF, SVG)
3. Maximum file size: 2MB
4. Recommended: Square image (e.g., 200x200px or 500x500px)

### Step 3: Update Hotel Name
1. Go to **General Settings** tab
2. Update **Hotel Name** field
3. This name will appear in the sidebar across all portals

### Step 4: Save Changes
1. Scroll to bottom of page
2. Click **Save Settings** button
3. Logo and name will update immediately across all panels

## Implementation Details

### File Locations

**Admin Sidebar:**
- File: `resources/views/admin/partials/sidebar.blade.php`
- Logo source: `\App\Models\Setting::get('hotel_logo')`
- Name source: `\App\Models\Setting::get('hotel_name', 'Admin Hotel')`

**Staff Sidebar:**
- File: `resources/views/staff/partials/sidebar.blade.php`
- Logo source: `\App\Models\Setting::get('hotel_logo')`
- Name source: `\App\Models\Setting::get('hotel_name', 'Hotel') . ' - Staff'`

**Guest Sidebar:**
- File: `resources/views/guest/partials/sidebar.blade.php`
- Logo source: `\App\Models\Setting::get('hotel_logo')`
- Name source: `\App\Models\Setting::get('hotel_name', 'Hotel') . ' - Guest'`

**Settings Controller:**
- File: `app/Http/Controllers/SettingController.php`
- Handles logo upload and saves to `uploads/settings/`
- Saves path to both `logo` and `hotel_logo` keys for compatibility

### Code Example

```php
<!-- Admin Sidebar Brand -->
<a href="{{ route('admin.dashboard') }}" class="brand-link">
    @if(\App\Models\Setting::get('hotel_logo'))
        <img src="{{ asset(\App\Models\Setting::get('hotel_logo')) }}" 
             alt="{{ \App\Models\Setting::get('hotel_name', 'Hotel') }}" 
             class="brand-image img-circle elevation-3" 
             style="opacity: .8; max-height: 33px; object-fit: cover;">
    @else
        <img src="{{asset('backend')}}/dist/img/AdminLTELogo.png" 
             alt="Hotel Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity: .8">
    @endif
    <span class="brand-text font-weight-light">
        {{ \App\Models\Setting::get('hotel_name', 'Admin Hotel') }}
    </span>
</a>
```

## Database Schema

### Settings Table
```
key: hotel_logo
value: uploads/settings/logo_1234567890.png
group: branding

key: logo (backward compatibility)
value: uploads/settings/logo_1234567890.png
group: branding

key: hotel_name
value: Grand Hotel
group: general

```

## Logo Specifications

### Recommended Dimensions
- **Minimum:** 100 x 100 pixels
- **Recommended:** 200 x 200 pixels or 500 x 500 pixels
- **Maximum file size:** 2MB
- **Format:** PNG (with transparency) or JPEG
- **Shape:** Square works best for circular display

### Design Tips
1. Use high contrast colors for visibility
2. Keep design simple - small display size
3. Consider transparency (PNG) for non-square logos
4. Test with both light and dark backgrounds
5. Ensure logo is legible at 33px height

## Fallback Behavior

### No Logo Uploaded
- System displays default AdminLTE logo
- Path: `public/backend/dist/img/AdminLTELogo.png`
- Blue circular logo with white "A" symbol

### No Hotel Name Set
- Admin portal: "Admin Hotel"
- Staff portal: "Hotel - Staff"
- Guest portal: "Hotel - Guest"

## File Storage

### Logo Upload Location
- Directory: `public/uploads/settings/`
- Naming convention: `logo_[timestamp].[extension]`
- Example: `logo_1738483200.png`

### Old Logo Cleanup
- When new logo uploaded, old logo automatically deleted
- Prevents disk space accumulation
- Implemented in `SettingController::update()`

## Troubleshooting

### Logo Not Displaying
1. **Check file exists:**
   ```bash
   ls -la public/uploads/settings/
   ```

2. **Verify database setting:**
   - Check Settings page shows uploaded logo
   - Verify path in database: `SELECT * FROM settings WHERE key='hotel_logo'`

3. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   ```

4. **Check file permissions:**
   ```bash
   chmod 755 public/uploads/settings/
   chmod 644 public/uploads/settings/*
   ```

### Logo Appears Distorted
- Use `object-fit: cover` CSS property (already applied)
- Upload square image for best results
- Check image aspect ratio

### Logo Too Large/Small
- Maximum display height: 33px (fixed)
- Adjust `max-height` value in sidebar blade files if needed
- Upload higher resolution image for crisp display

### Hotel Name Not Updating
1. Verify saved in database
2. Clear application cache
3. Hard refresh browser (Ctrl+Shift+R)
4. Check Settings model has correct getter

## Browser Compatibility

Tested on:
- ✅ Chrome 120+
- ✅ Firefox 121+
- ✅ Edge 120+
- ✅ Safari 17+

All browsers correctly display:
- Dynamic logos with circular mask
- Image fit and scaling
- Shadow effects
- Fallback behavior

## Migration Guide

### From Hardcoded to Dynamic Logo

**Before:**
```php
<img src="{{asset('backend')}}/dist/img/AdminLTELogo.png" alt="Logo">
<span>Admin Hotel</span>
```

**After:**
```php
@if(\App\Models\Setting::get('hotel_logo'))
    <img src="{{ asset(\App\Models\Setting::get('hotel_logo')) }}" alt="{{ \App\Models\Setting::get('hotel_name') }}">
@else
    <img src="{{asset('backend')}}/dist/img/AdminLTELogo.png" alt="Logo">
@endif
<span>{{ \App\Models\Setting::get('hotel_name', 'Admin Hotel') }}</span>
```

## Security Considerations

### File Upload Validation
```php
'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
```

- Only image files allowed
- Maximum 2MB file size
- Limited to safe image formats
- Server-side validation enforced

### File Storage Security
- Files stored in `public/uploads/settings/`
- Direct URL access allowed (public assets)
- No sensitive data in filenames
- Old files automatically deleted

## Performance

### Load Time Impact
- Logo loaded from local server (no external requests)
- Cached by browser after first load
- Minimal impact: ~10-50KB per logo
- No database queries (Settings model cached)

### Optimization Tips
1. Use compressed PNG or optimized JPEG
2. Recommended file size: 20-100KB
3. Consider using WebP format for modern browsers
4. Enable browser caching for `/uploads/` directory

## Future Enhancements

Potential improvements:
- [ ] Logo upload preview before save
- [ ] Multiple logo variants (light/dark theme)
- [ ] Logo dimensions validation
- [ ] Automatic image compression
- [ ] Logo history/versioning
- [ ] Separate logos for each portal
- [ ] Custom portal names (not just "Staff"/"Guest")

## Related Files

- [admin/partials/sidebar.blade.php](resources/views/admin/partials/sidebar.blade.php)
- [staff/partials/sidebar.blade.php](resources/views/staff/partials/sidebar.blade.php)
- [guest/partials/sidebar.blade.php](resources/views/guest/partials/sidebar.blade.php)
- [SettingController.php](app/Http/Controllers/SettingController.php)
- [settings/index.blade.php](resources/views/settings/index.blade.php)

## Support

For issues or questions:
1. Check this documentation first
2. Verify file permissions and uploads directory
3. Review Settings page for correct values
4. Check Laravel logs: `storage/logs/laravel.log`

---

**Last Updated:** February 2, 2026
**Version:** 1.0
**Status:** ✅ Production Ready
