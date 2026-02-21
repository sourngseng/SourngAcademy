<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','AdminLTE 3 | Blank Page')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:300,400,400i,700&family=Inter:wght@400;600;700&family=Outfit:wght@400;600;700&family=Kantumruy+Pro:wght@400;600;700&family=Hanuman:wght@400;700&family=Noto+Sans+Khmer:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome (Local + CDN Fallback) -->
    <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('backend/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Tempus Dominus Bootstrap 4 DateTime Picker -->
    <link rel="stylesheet" href="{{asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    
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
    </style>
    
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini" data-lang="{{ app()->getLocale() }}">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.partials.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('page-title')

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> {{ config('app.version') }}
            </div>
            <strong>Copyright &copy; {{now()->year}} <a href="https://sourng.com">SOURNG.COM</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{asset('backend')}}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('backend')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="{{asset('backend/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Moment.js (required for DateTime Picker) -->
    <script src="{{asset('backend/plugins/moment/moment.min.js')}}"></script>
    <!-- Tempus Dominus Bootstrap 4 DateTime Picker -->
    <script src="{{asset('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <!-- AdminLTE App -->
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
    
    <!-- SweetAlert2 (Global) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif
            
            @if(session('info'))
                Toast.fire({
                    icon: 'info',
                    title: "{{ session('info') }}"
                });
            @endif
            
            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>