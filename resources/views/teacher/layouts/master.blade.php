<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Portal') - {{ \App\Models\Setting::get('university_name', config('app.name', 'Sourng Academy')) }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:300,400,400i,700&family=Inter:wght@400;600;700&family=Outfit:wght@400;600;700&family=Kantumruy+Pro:wght@400;600;700&family=Hanuman:wght@400;700&family=Noto+Sans+Khmer:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('backend')}}/dist/css/adminlte.min.css?v=3.2.0">
    
    <style>
        :root {
            --primary-font: {!! \App\Models\Setting::get('primary_font', "'Source Sans Pro', sans-serif") !!};
            --khmer-font: {!! \App\Models\Setting::get('khmer_font', "'Kantumruy Pro', sans-serif") !!};
        }
        
        body {
            font-family: var(--primary-font);
        }
        
        [lang="km"], .khmer-text, body[data-lang="km"] {
            font-family: var(--khmer-font) !important;
        }

        /* Support for Khmer font in specific elements when in Khmer mode */
        @if(app()->getLocale() == 'km')
        body, h1, h2, h3, h4, h5, h6, .nav-link, .brand-text, .btn {
            font-family: var(--khmer-font) !important;
        }
        @endif

        .main-sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%) !important;
        }
        .brand-link {
            background: rgba(0,0,0,0.1) !important;
        }
        .nav-sidebar .nav-link.active {
            background: #2563eb !important;
        }
    </style>
    
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('teacher.partials.header')

        <!-- Sidebar -->
        @include('teacher.partials.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Teacher Portal</b>
            </div>
            <strong>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('university_name', 'Sourng Academy') }}</strong>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{asset('backend')}}/plugins/jquery/jquery.min.js"></script>
    <script src="{{asset('backend')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('backend')}}/dist/js/adminlte.min.js?v=3.2.0"></script>
    
    <!-- Dark Mode Script -->
    <script>
        $(document).ready(function() {
            var toggleSwitch = $('#darkModeToggle');
            var icon = toggleSwitch.find('i');
            var body = $('body');
            var navbar = $('.main-header');

            function enableDarkMode() {
                body.addClass('dark-mode');
                navbar.removeClass('navbar-white navbar-light').addClass('navbar-dark');
                icon.removeClass('fa-moon').addClass('fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            }

            function disableDarkMode() {
                body.removeClass('dark-mode');
                navbar.removeClass('navbar-dark').addClass('navbar-white navbar-light');
                icon.removeClass('fa-sun').addClass('fa-moon');
                localStorage.setItem('darkMode', 'disabled');
            }

            // Initialize
            if (localStorage.getItem('darkMode') === 'enabled') {
                enableDarkMode();
            }

            // Toggle
            toggleSwitch.on('click', function(e) {
                e.preventDefault();
                if (body.hasClass('dark-mode')) {
                    disableDarkMode();
                } else {
                    enableDarkMode();
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
