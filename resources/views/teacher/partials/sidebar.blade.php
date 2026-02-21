<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('teacher.dashboard') }}" class="brand-link">
        @if(\App\Models\Setting::get('university_logo'))
            <img src="{{ asset(\App\Models\Setting::get('university_logo')) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @else
            <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light">{{ \App\Models\Setting::get('university_name', 'Teacher Portal') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ Request::routeIs('teacher.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                
                <li class="nav-header">{{ __('ACADEMIC') }}</li>
                <li class="nav-item">
                    <a href="{{ route('teacher.myclasses.index') }}" class="nav-link {{ Request::routeIs('teacher.myclasses.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>{{ __('My Classes') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.dashboard') }}#students" class="nav-link">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>{{ __('My Students') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-signature"></i>
                        <p>{{ __('Assignments') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.session-plans.index') }}" class="nav-link {{ Request::routeIs('teacher.session-plans.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>{{ __('My Lesson Plans') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>{{ __('Attendance') }}</p>
                    </a>
                </li>
                
                <li class="nav-header">{{ __('USER') }}</li>
                <li class="nav-item">
                    <a href="{{ route('teacher.profile.edit') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ __('My Profile') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ __('Logout') }}</p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
