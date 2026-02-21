<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Guest Portal') - {{ App\Models\Setting::get('hotel_name', 'Hotel MIS') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome (Local + CDN Fallback) -->
    <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tempus Dominus Bootstrap 4 DateTime Picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css">
    <!-- Khmer Fonts -->
    <link rel="stylesheet" href="{{asset('fonts/khmer-fonts.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('backend')}}/dist/css/adminlte.min.css?v=3.2.0">
    
    <style>
        .main-sidebar {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%) !important;
        }
        .brand-link {
            background: rgba(0,0,0,0.1) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }
        .brand-link .brand-text {
            color: #fff !important;
        }
        .nav-sidebar .nav-link {
            color: rgba(255,255,255,0.8) !important;
        }
        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }
        .nav-sidebar .nav-link.active {
            background: rgba(255,255,255,0.2) !important;
            color: #fff !important;
        }
        .nav-header {
            color: rgba(255,255,255,0.5) !important;
        }
        .sidebar .user-panel .info a {
            color: #fff !important;
        }
        .sidebar .user-panel .info small {
            color: rgba(255,255,255,0.7) !important;
        }
        .stat-card {
            border-radius: 15px;
            padding: 25px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .stat-card .icon {
            font-size: 40px;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-card p {
            margin: 0;
            opacity: 0.85;
        }
        .guest-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .guest-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        .guest-card .card-header {
            background: transparent;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        .booking-card {
            border-radius: 10px;
            border: 1px solid #eee;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .booking-card:hover {
            border-color: #667eea;
            box-shadow: 0 3px 15px rgba(102, 126, 234, 0.1);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: #fff;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
    
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini" @if(app()->getLocale() == 'km' && \App\Models\Setting::get('khmer_font')) data-khmer-font="{{ \App\Models\Setting::get('khmer_font') }}" @endif>
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('guest.partials.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('guest.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('page-title')

            <!-- Main content -->
            <section class="content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Guest Portal</b>
            </div>
            <strong>{{ App\Models\Setting::get('hotel_name', 'Hotel MIS') }}</strong> - Your Home Away From Home
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
    <!-- Moment.js (required for DateTime Picker) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- Tempus Dominus Bootstrap 4 DateTime Picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('backend')}}/dist/js/adminlte.min.js?v=3.2.0"></script>
    
    @stack('scripts')
</body>

</html>
