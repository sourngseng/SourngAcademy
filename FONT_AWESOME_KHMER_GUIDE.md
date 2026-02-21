# Font Awesome + Khmer Fonts Integration Guide

## 🎯 Overview
This project successfully integrates **Font Awesome icons** with **Khmer fonts (Hanuman & Kantumruy Pro)** ensuring both work together without conflicts.

## ✨ Key Features

### Font Awesome
- **Local Font Awesome 5** - Hosted in `public/backend/plugins/fontawesome-free/`
- **CDN Font Awesome 6** - Fallback for latest icons with SRI integrity
- **Three Icon Styles**: Solid (fas), Regular (far), Brands (fab)
- **15 Webfont Files** (2.9MB) supporting all formats (.eot, .svg, .ttf, .woff, .woff2)

### Khmer Fonts
- **Hanuman** - 5 weights (Thin, Light, Regular, Bold, Black) - 1.5MB
- **Kantumruy Pro** - 7 weights (Thin to Bold) - 2.0MB
- **Admin Configuration** - Choose font from Settings page
- **Locale-Based** - Auto-applies when `app()->getLocale() == 'km'`

## 📁 File Structure

```
public/
├── fonts/
│   ├── khmer-fonts.css           # Main CSS with @font-face + utilities
│   ├── hanuman/
│   │   ├── Hanuman-*.ttf         # 5 font files
│   │   └── README.md
│   └── kantumruy-pro/
│       ├── KantumruyPro-*.ttf    # 7 font files
│       └── README.md
└── backend/
    └── plugins/
        └── fontawesome-free/
            ├── css/
            │   └── all.min.css   # Font Awesome CSS
            └── webfonts/         # 15 font files
```

## 🔧 Technical Implementation

### CSS Selectors Strategy
The key to making Font Awesome work with Khmer fonts is **selective targeting**:

```css
/* ✅ CORRECT: Applies only to text elements, excludes Font Awesome */
body[data-khmer-font="hanuman"] p,
body[data-khmer-font="hanuman"] span:not([class*="fa"]),
body[data-khmer-font="hanuman"] h1,
body[data-khmer-font="hanuman"] label {
  font-family: 'Hanuman', sans-serif !important;
}

/* ❌ WRONG: Would break Font Awesome icons */
body[data-khmer-font="hanuman"] * {
  font-family: 'Hanuman', sans-serif !important;
}
```

### Font Awesome Protection
Explicit Font Awesome rules ensure icons always render correctly:

```css
i[class*="fa-"],
.fa, .fas, .far, .fal, .fab {
  font-family: "Font Awesome 6 Free", "Font Awesome 5 Free" !important;
}
```

### Layout Integration
All layouts include both Font Awesome and Khmer fonts:

```php
<!-- Admin Layout: resources/views/admin/layouts/master.blade.php -->
<link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<link rel="stylesheet" href="{{asset('fonts/khmer-fonts.css')}}">

<body data-khmer-font="{{ \App\Models\Setting::get('khmer_font') }}">
```

## 🎨 Usage Examples

### Basic Icons
```html
<i class="fas fa-home"></i>              <!-- Home icon -->
<i class="fas fa-hotel"></i>             <!-- Hotel icon -->
<i class="far fa-heart"></i>             <!-- Regular heart -->
<i class="fab fa-facebook"></i>          <!-- Facebook brand -->
```

### Icons with Khmer Text
```html
<h3><i class="fas fa-hotel"></i> សណ្ឋាគារ</h3>
<button><i class="fas fa-save"></i> រក្សាទុក</button>
<li><i class="fas fa-bed text-primary"></i> បន្ទប់</li>
```

### Icon Sizes
```html
<i class="fas fa-star fa-xs"></i>        <!-- Extra small -->
<i class="fas fa-star fa-sm"></i>        <!-- Small -->
<i class="fas fa-star"></i>              <!-- Normal -->
<i class="fas fa-star fa-lg"></i>        <!-- Large -->
<i class="fas fa-star fa-2x"></i>        <!-- 2x -->
<i class="fas fa-star fa-3x"></i>        <!-- 3x -->
```

### Icon Colors
```html
<i class="fas fa-check text-success"></i>
<i class="fas fa-times text-danger"></i>
<i class="fas fa-info-circle text-info"></i>
<i class="fas fa-exclamation-triangle text-warning"></i>
```

### AdminLTE Components
```html
<!-- Info Box -->
<div class="info-box">
  <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
  <div class="info-box-content">
    <span class="info-box-text">ការកក់</span>
    <span class="info-box-number">150</span>
  </div>
</div>

<!-- Small Box -->
<div class="small-box bg-success">
  <div class="inner">
    <h3>53</h3>
    <p>ភ្ញៀវថ្មី</p>
  </div>
  <div class="icon">
    <i class="fas fa-user-plus"></i>
  </div>
</div>
```

## 🧪 Testing Pages

### 1. Font Awesome Icons Reference
**URL:** `http://localhost:8000/admin/fontawesome`

Features:
- Icon category overview (Solid, Regular, Brands)
- 100+ common icons with examples
- Hotel & tourism specific icons
- Business & finance icons
- Brand icons with correct colors
- Usage instructions and code snippets

### 2. Font Awesome + Khmer Demo
**URL:** `http://localhost:8000/admin/font-demo`

Features:
- **Live Font Switcher** - Switch between System/Hanuman/Kantumruy
- Hotel management examples with Khmer translations
- Statistics cards with icons
- 24 icon + text combinations
- Form example with labeled inputs
- Alert components with icons
- All icons remain unchanged when switching fonts

## 🎯 Common Use Cases

### Navigation Menu
```html
<li class="nav-item">
  <a href="{{ route('admin.dashboard') }}" class="nav-link">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>{{ __('Dashboard') }}</p>  <!-- ផ្ទាំងគ្រប់គ្រង -->
  </a>
</li>
```

### Buttons
```html
<button class="btn btn-primary">
  <i class="fas fa-save"></i> រក្សាទុក
</button>
<button class="btn btn-danger">
  <i class="fas fa-trash"></i> លុប
</button>
```

### DataTables Actions
```html
<a href="#" class="btn btn-info btn-sm">
  <i class="fas fa-eye"></i>
</a>
<a href="#" class="btn btn-primary btn-sm">
  <i class="fas fa-edit"></i>
</a>
<button class="btn btn-danger btn-sm">
  <i class="fas fa-trash"></i>
</button>
```

### Breadcrumbs
```html
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="/"><i class="fas fa-home"></i> Home</a>
  </li>
  <li class="breadcrumb-item active">ការកក់</li>
</ol>
```

## 📋 Icon Categories

### Hotel & Tourism (12 icons)
- `fa-hotel` - Hotel
- `fa-bed` - Bed
- `fa-door-open` - Door
- `fa-key` - Key
- `fa-calendar-check` - Booking
- `fa-sign-in-alt` - Check-in
- `fa-sign-out-alt` - Check-out
- `fa-wifi` - WiFi
- `fa-swimming-pool` - Pool
- `fa-utensils` - Restaurant
- `fa-parking` - Parking
- `fa-dumbbell` - Gym

### Business & Finance (12 icons)
- `fa-dollar-sign` - Money
- `fa-credit-card` - Payment
- `fa-chart-pie` - Statistics
- `fa-chart-bar` - Reports
- `fa-chart-line` - Trends
- `fa-receipt` - Receipt
- `fa-file-invoice-dollar` - Invoice
- `fa-wallet` - Wallet
- `fa-coins` - Coins
- `fa-money-bill-wave` - Bills
- `fa-piggy-bank` - Savings
- `fa-calculator` - Calculator

### Social & Brands (12 icons)
- `fa-facebook` - #1877f2
- `fa-twitter` - #1da1f2
- `fa-instagram` - #e4405f
- `fa-youtube` - #ff0000
- `fa-linkedin` - #0077b5
- `fa-github` - #333333
- `fa-google` - #4285f4
- `fa-whatsapp` - #25d366
- `fa-telegram` - #0088cc
- `fa-stripe` - #635bff
- `fa-paypal` - #0070ba
- `fa-cc-visa` - #1a1f71

## 🔍 Troubleshooting

### Icons Not Showing
1. **Check Font Awesome CSS loaded:**
   ```html
   View Page Source → Search for "fontawesome" or "all.min.css"
   ```

2. **Verify icon class syntax:**
   ```html
   ✅ <i class="fas fa-home"></i>
   ❌ <i class="fa fa-home"></i> (old Font Awesome 4 syntax)
   ```

3. **Clear browser cache:**
   ```
   Ctrl + Shift + R (Windows/Linux)
   Cmd + Shift + R (Mac)
   ```

### Khmer Text Overriding Icons
1. **Check CSS specificity** - Font Awesome rules should have `!important`
2. **Verify selectors** - Should use `:not([class*="fa"])` exclusions
3. **Inspect element** - Check computed font-family of icon

### Font Not Changing
1. **Check locale** - Khmer fonts only apply when `app()->getLocale() == 'km'`
2. **Check setting** - Verify `khmer_font` exists in settings table
3. **Check body attribute** - Should have `data-khmer-font="hanuman"` or `"kantumruy"`

## 📦 Resources

### Font Awesome
- **Official Site:** https://fontawesome.com
- **Icon Search:** https://fontawesome.com/search
- **Documentation:** https://fontawesome.com/docs
- **Local Version:** Font Awesome 5.15.4
- **CDN Version:** Font Awesome 6.5.1

### Khmer Fonts
- **Source:** Google Fonts (GitHub)
- **License:** Open Font License (OFL)
- **Hanuman:** https://fonts.google.com/specimen/Hanuman
- **Kantumruy Pro:** https://fonts.google.com/specimen/Kantumruy+Pro

## 🚀 Performance

### Font Loading Strategy
- **font-display: swap** - Text visible immediately, font swaps when loaded
- **Local hosting** - No external requests for Khmer fonts
- **Dual CDN** - Font Awesome has local + CDN fallback
- **Total Size:** ~6.4MB (2.9MB FA + 3.5MB Khmer fonts)

### Optimization Tips
1. Use icon fonts instead of SVG for better performance
2. Only load Font Awesome CSS (don't load unused Pro fonts)
3. Khmer fonts auto-load only when needed (km locale)
4. CDN Font Awesome has HTTP/2 and compression

## 📝 Code References

### Main Files
- [public/fonts/khmer-fonts.css](../public/fonts/khmer-fonts.css) - Font integration CSS
- [resources/views/admin/layouts/master.blade.php](../resources/views/admin/layouts/master.blade.php#L13-L25) - Admin layout with fonts
- [resources/views/admin/fontawesome.blade.php](../resources/views/admin/fontawesome.blade.php) - Icon reference page
- [resources/views/admin/font-demo.blade.php](../resources/views/admin/font-demo.blade.php) - Interactive demo
- [routes/web.php](../routes/web.php#L30-L35) - Demo routes

### Related Guides
- [KHMER_FONTS_GUIDE.md](./KHMER_FONTS_GUIDE.md) - Detailed Khmer fonts documentation
- [README.md](../README.md) - Project overview

---

**Last Updated:** February 2, 2026
**Version:** 1.0
**Author:** Hotel MIS Development Team
