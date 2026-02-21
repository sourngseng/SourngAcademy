<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ Auth::check() ? route('guest.dashboard') : url('/') }}" class="brand-link">
        @if(\App\Models\Setting::get('hotel_logo'))
            <img src="{{ asset(\App\Models\Setting::get('hotel_logo')) }}" alt="{{ \App\Models\Setting::get('hotel_name', 'Hotel') }}" class="brand-image img-circle elevation-3" style="opacity: .8; max-height: 33px; object-fit: cover;">
        @else
            <img src="{{asset('backend')}}/dist/img/AdminLTELogo.png" alt="Hotel Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light">{{ \App\Models\Setting::get('app_name', 'Hotel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @auth
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(Auth::user()->profile_image)
                    <img src="{{ asset(Auth::user()->profile_image) }}" class="img-circle elevation-2" alt="User Image" style="width: 34px; height: 34px; object-fit: cover;">
                @else
                    <img src="{{ asset('backend/dist/img/default-avatar.png') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('guest.profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small>{{ __('Valued Guest') }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('guest.dashboard') }}" class="nav-link {{ Request::routeIs('guest.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('ACADEMIC') }}</li>

                <li class="nav-item">
                    <a href="{{ url('/student/classes') }}" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                            {{ __('My Classes') }}
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ url('/student/schedule') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            {{ __('Timetable') }}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/student/results') }}" class="nav-link">
                        <i class="nav-icon fas fa-poll"></i>
                        <p>
                            {{ __('My Results') }}
                        </p>
                    </a>
                </li>

                <li class="nav-header">{{ __('ACCOUNT') }}</li>

                <li class="nav-item">
                    <a href="{{ route('guest.profile.edit') }}" class="nav-link {{ Request::routeIs('guest.profile.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>{{ __('My Profile') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('HELP') }}</li>

                <li class="nav-item">
                    <a href="mailto:{{ App\Models\Setting::get('hotel_email', 'support@hotel.com') }}" class="nav-link">
                        <i class="nav-icon fas fa-headset"></i>
                        <p>{{ __('Contact Support') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
        @else
        <!-- Guest (not logged in) Menu -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <p class="text-white">{{ __('Welcome, Guest!') }}</p>
                <small>{{ __('Please login to continue') }}</small>
            </div>
        </div>
        
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('guest.bookings.create') }}" class="nav-link {{ Request::routeIs('guest.bookings.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-plus"></i>
                        <p>{{ __('Book Now') }}</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-in-alt"></i>
                        <p>{{ __('Login') }}</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>{{ __('Register') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        @endauth
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
