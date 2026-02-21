# Khmer Font Integration - Hotel MIS

## Overview
This project includes two beautiful Google Khmer fonts that can be configured through the admin settings:
- **Hanuman** - A traditional Khmer font with elegant serifs
- **Kantumruy Pro** - A modern, clean sans-serif Khmer font

## Features
✅ **Automatic Font Application** - Fonts automatically apply when Khmer (km) language is selected
✅ **Admin Configuration** - Choose your preferred Khmer font in Hotel Settings → General Settings
✅ **Local Hosting** - All fonts are hosted locally (no external dependencies)
✅ **Multiple Weights** - Both fonts include Thin, Light, Regular, Medium, SemiBold, and Bold weights
✅ **Optimized Loading** - Fonts use `font-display: swap` for optimal performance

## Font Files Location
```
public/fonts/
├── khmer-fonts.css          # Main CSS file with @font-face declarations
├── hanuman/                 # Hanuman font family
│   ├── Hanuman-Thin.ttf
│   ├── Hanuman-Light.ttf
│   ├── Hanuman-Regular.ttf
│   ├── Hanuman-Bold.ttf
│   └── Hanuman-Black.ttf
└── kantumruy-pro/           # Kantumruy Pro font family
    ├── KantumruyPro-Thin.ttf
    ├── KantumruyPro-ExtraLight.ttf
    ├── KantumruyPro-Light.ttf
    ├── KantumruyPro-Regular.ttf
    ├── KantumruyPro-Medium.ttf
    ├── KantumruyPro-SemiBold.ttf
    └── KantumruyPro-Bold.ttf
```

## How to Use

### 1. Access Settings
Navigate to: **Admin Panel → Hotel Settings → General Settings**

### 2. Select Khmer Font
Find the "Khmer Font Selection" dropdown and choose:
- **System Default** - Uses browser's default Khmer font
- **Hanuman** - Traditional serif Khmer font
- **Kantumruy Pro** - Modern sans-serif Khmer font

### 3. Preview
A live preview of the selected font will appear in the preview card

### 4. Save Settings
Click "Save Settings" at the bottom of the page

### 5. Switch to Khmer Language
Use the language switcher in the header to change to Khmer (ខ្មែរ)
- The selected font will automatically apply to all Khmer text

## Technical Implementation

### CSS Classes
The system provides utility classes for manual font application:

```html
<!-- Apply Hanuman font -->
<div class="khmer-font-hanuman">
    ជំរាបសួរ
</div>

<!-- Apply Kantumruy Pro font -->
<div class="khmer-font-kantumruy">
    ជំរាបសួរ
</div>
```

### Data Attribute (Automatic)
When Khmer locale is active and a font is selected in settings:
```html
<body data-khmer-font="hanuman">
    <!-- All Khmer text will use Hanuman font -->
</body>
```

### Database Setting
The font preference is stored in the `settings` table:
- **Key**: `khmer_font`
- **Values**: `hanuman`, `kantumruy`, or empty (system default)

### Code References
- **Master Layout**: `resources/views/admin/layouts/master.blade.php` (line 19 & 23)
- **CSS File**: `public/fonts/khmer-fonts.css`
- **Settings Controller**: `app/Http/Controllers/SettingController.php` (line 59)
- **Settings View**: `resources/views/settings/index.blade.php` (line 127-169)

## Font Credits
- **Hanuman**: Designed by Danh Hong - [Google Fonts](https://fonts.google.com/specimen/Hanuman)
- **Kantumruy Pro**: Designed by Tep Sovichet - [Google Fonts](https://fonts.google.com/specimen/Kantumruy+Pro)

## License
Both fonts are licensed under the [Open Font License (OFL)](https://scripts.sil.org/OFL), which allows free commercial and non-commercial use.

## Troubleshooting

### Fonts Not Loading?
1. Ensure font files exist in `public/fonts/hanuman/` and `public/fonts/kantumruy-pro/`
2. Check browser console for 404 errors
3. Verify CSS file is included in master layout
4. Clear browser cache (Ctrl+Shift+R)

### Font Not Applying?
1. Make sure Khmer language (km) is selected
2. Verify setting is saved in database: `SELECT * FROM settings WHERE key='khmer_font'`
3. Check `data-khmer-font` attribute on `<body>` tag
4. Clear Laravel cache: `php artisan cache:clear`

### Font Looks Wrong?
1. Try a different font from the dropdown
2. Ensure you're viewing actual Khmer text (not English)
3. Check line-height CSS rules (increased to 1.8 for readability)

## Re-downloading Fonts
If font files are missing or corrupted, run:
```bash
cd public/fonts
bash download-fonts.sh
```

Or manually download from:
- Hanuman: https://github.com/google/fonts/tree/main/ofl/hanuman
- Kantumruy Pro: https://github.com/google/fonts/tree/main/ofl/kantumruypro

## Future Enhancements
- [ ] Add more Khmer font options (Battambang, Siemreap, etc.)
- [ ] Frontend font selector for guests
- [ ] Per-page font overrides
- [ ] Font weight customization
- [ ] Custom font upload support
