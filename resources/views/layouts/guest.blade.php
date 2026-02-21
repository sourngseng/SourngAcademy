<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
            $hotelName = $settings['hotel_name'] ?? config('app.name', 'Hotel MIS');
            $logo = $settings['logo'] ?? null;
        @endphp

        <title>{{ $hotelName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Khmer Fonts -->
        <link rel="stylesheet" href="{{asset('fonts/khmer-fonts.css')}}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for interactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        @php
            $khmerFont = \App\Models\Setting::get('khmer_font', 'Kantumruy Pro');
            $isKhmer = app()->getLocale() == 'km';
        @endphp
        <style>
            body {
                font-family: @if($isKhmer) '{{ $khmerFont }}', @endif 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: @if($isKhmer) '{{ $khmerFont }}', @endif 'Playfair Display', serif;
            }
            @if($isKhmer)
            /* Khmer font adjustments */
            .text-sm { font-size: 0.9rem; }
            .text-xs { font-size: 0.8rem; }
            @endif
        </style>
    </head>
    <body class="antialiased" @if($isKhmer) data-khmer-font="{{ $khmerFont }}" @endif>
        <div class="min-h-screen flex bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
            </div>
            
            <!-- Language Selector - Fixed Position -->
            @php
                $availableLanguages = \App\Http\Controllers\Admin\LanguageController::getAvailableLanguages();
                $currentLocale = app()->getLocale();
                $currentLang = $availableLanguages[$currentLocale] ?? ['name' => 'English', 'flag' => '🌐'];
            @endphp
            <div class="absolute top-4 right-4 z-50">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl text-white hover:bg-white/20 transition-all">
                        <span class="text-lg">{{ $currentLang['flag'] }}</span>
                        <span class="text-sm font-medium">{{ $currentLang['name'] }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl shadow-2xl overflow-hidden"
                         style="display: none;">
                        @foreach($availableLanguages as $locale => $data)
                            <a href="{{ route('language.switch', $locale) }}" 
                               class="flex items-center space-x-3 px-4 py-3 text-white hover:bg-white/20 transition-colors {{ $currentLocale === $locale ? 'bg-amber-500/20' : '' }}">
                                <span class="text-lg">{{ $data['flag'] }}</span>
                                <span class="text-sm font-medium">{{ $data['name'] }}</span>
                                @if($currentLocale === $locale)
                                    <svg class="w-4 h-4 ml-auto text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Desktop Layout: Two Columns -->
            <div class="relative w-full flex flex-col lg:flex-row">
                <!-- Left Side - Branding (Hidden on Mobile) -->
                <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12">
                    <div class="max-w-md text-center lg:text-left">
                        <!-- Logo -->
                        <a href="/" class="inline-flex items-center space-x-3 mb-8">
                            @if($logo)
                                <img src="{{ asset($logo) }}" alt="{{ $hotelName }}" class="h-16 w-auto object-contain">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            <span class="text-3xl font-bold text-white">{{ $hotelName }}</span>
                        </a>
                        
                        <!-- Welcome Text -->
                        <h1 class="text-4xl font-bold text-white mb-4">{{ $title ?? 'Admin Portal' }}</h1>
                        <p class="text-xl text-gray-300 mb-8">{{ __('Sign in to access your dashboard') }}</p>
                        
                        <!-- Features -->
                        <div class="space-y-4">
                            <div class="flex items-center text-gray-300">
                                <svg class="w-6 h-6 text-amber-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('Complete booking management') }}</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <svg class="w-6 h-6 text-amber-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('Real-time room availability') }}</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <svg class="w-6 h-6 text-amber-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('Secure and reliable system') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Form -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
                    <div class="w-full max-w-md space-y-8">
                        <!-- Mobile Logo (Visible only on mobile) -->
                        <div class="lg:hidden text-center">
                            <a href="/" class="inline-flex items-center space-x-2 justify-center">
                                @if($logo)
                                    <img src="{{ asset($logo) }}" alt="{{ $hotelName }}" class="h-12 w-auto object-contain">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif
                                <span class="text-2xl font-bold text-white">{{ $hotelName }}</span>
                            </a>
                            <h2 class="mt-6 text-3xl font-bold text-white">{{ $title ?? 'Admin Portal' }}</h2>
                            <p class="mt-2 text-gray-400">{{ __('Sign in to manage your hotel') }}</p>
                        </div>
                        
                        <!-- Form Card -->
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
