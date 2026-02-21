<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $meta_title ?? \App\Models\Setting::get('university_name', config('app.name', 'Sourng Academy')) }}</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="{{ $meta_description ?? 'Sourng Academy - Excellence in Education, Innovation, and Research' }}">
    <meta property="og:title" content="{{ $meta_title ?? \App\Models\Setting::get('university_name', config('app.name', 'Sourng Academy')) }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Sourng Academy - Excellence in Education, Innovation, and Research' }}">
    <meta property="og:image" content="{{ $meta_image ?? asset('uploads/logo/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta_title ?? \App\Models\Setting::get('university_name', config('app.name', 'Sourng Academy')) }}">
    <meta name="twitter:description" content="{{ $meta_description ?? 'Sourng Academy - Excellence in Education, Innovation, and Research' }}">
    <meta name="twitter:image" content="{{ $meta_image ?? asset('uploads/logo/logo.png') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/uploads/logo/favicon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:400,500,600,700,700i&display=swap" rel="stylesheet" />
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
    
    @php
        $universitySettings = [
            'name' => \App\Models\Setting::get('university_name', config('app.name', 'Sourng Academy')),
            'currency' => 'USD',
            'locale' => app()->getLocale(),
            'telegram_bot_username' => \App\Models\Setting::get('telegram_bot_username')
        ];
        
        $authUser = auth()->user() ? [
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'avatar' => auth()->user()->profile_image ? asset(auth()->user()->profile_image) : null
        ] : null;
    @endphp
    
    <script>
        // Pass data from Laravel to Vue
        window.universitySettings = @json($universitySettings);
        window.authUser = @json($authUser);

        // Apply dark mode immediately to prevent flash
        (function() {
            const savedDarkMode = localStorage.getItem('darkMode');
            if (savedDarkMode === 'enabled' || (!savedDarkMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark-mode');
                // Also add to body when it's available
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('dark-mode');
                });
            }
        })();
    </script>
</head>
<body class="font-sans antialiased">
    <div id="app"></div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</body>
</html>
