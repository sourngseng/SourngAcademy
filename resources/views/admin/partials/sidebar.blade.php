<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        @if(\App\Models\Setting::get('university_logo'))
            <img src="{{ asset(\App\Models\Setting::get('university_logo')) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @else
            <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light">{{ \App\Models\Setting::get('university_short_name', 'Sourng Academy') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <!-- UNIVERSITY MGMT -->
                <li class="nav-header">{{ __('UNIVERSITY MGMT') }}</li>
                <li class="nav-item">
                    <a href="{{ route('admin.campuses.index') }}" class="nav-link {{ Request::routeIs('admin.campuses.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-university"></i>
                        <p>{{ __('Campuses') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.classrooms.index') }}" class="nav-link {{ Request::routeIs('admin.classrooms.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>{{ __('Classrooms') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::routeIs('admin.faculties.*') || Request::routeIs('admin.departments.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::routeIs('admin.faculties.*') || Request::routeIs('admin.departments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>
                            {{ __('Faculties & Depts') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.faculties.index') }}" class="nav-link {{ Request::routeIs('admin.faculties.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Faculties') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.departments.index') }}" class="nav-link {{ Request::routeIs('admin.departments.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Departments') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::routeIs('admin.programs.*') || Request::routeIs('admin.subjects.*') || Request::routeIs('admin.program-subjects.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::routeIs('admin.programs.*') || Request::routeIs('admin.subjects.*') || Request::routeIs('admin.program-subjects.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            {{ __('Programs & Subjects') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.programs.index') }}" class="nav-link {{ Request::routeIs('admin.programs.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Programs') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ Request::routeIs('admin.subjects.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Subjects') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.program-subjects.index') }}" class="nav-link {{ Request::routeIs('admin.program-subjects.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Program Subjects') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ACADEMIC -->
                <li class="nav-header">{{ __('ACADEMIC') }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.academic-years.index') }}" class="nav-link {{ Request::routeIs('admin.academic-years.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>{{ __('Academic Years') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.degree-levels.index') }}" class="nav-link {{ Request::routeIs('admin.degree-levels.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-award"></i>
                        <p>{{ __('Degree Levels') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.semesters.index') }}" class="nav-link {{ Request::routeIs('admin.semesters.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>{{ __('Semesters') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.shifts.index') }}" class="nav-link {{ Request::routeIs('admin.shifts.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-business-time"></i>
                        <p>{{ __('Shift Management') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ Request::routeIs('admin.classes.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard"></i>
                        <p>{{ __('Class Management') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.session-plans.index') }}" class="nav-link {{ Request::routeIs('admin.session-plans.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>{{ __('Session Plans') }}</p>
                    </a>
                </li>

                <!-- USER MGMT -->
                <li class="nav-header">{{ __('USER MGMT') }}</li>
                @can('manage_users')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>{{ __('Users') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::routeIs('admin.roles.*') || Request::routeIs('admin.permissions.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::routeIs('admin.roles.*') || Request::routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            {{ __('Access Control') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ Request::routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Roles') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ Request::routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Permissions') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- CONTENT MGMT -->
                <li class="nav-header">{{ __('CONTENT MGMT') }}</li>
                <li class="nav-item">
                    <a href="{{ route('admin.slides.index') }}" class="nav-link {{ Request::routeIs('admin.slides.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>{{ __('Slider') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.blog.index') }}" class="nav-link {{ Request::routeIs('admin.blog.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-blog"></i>
                        <p>{{ __('Blog & Articles') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pages.index') }}" class="nav-link {{ Request::routeIs('admin.pages.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('Pages') }}</p>
                    </a>
                </li>

                <!-- FINANCE -->
                <li class="nav-header">{{ __('FINANCE') }}</li>
                <li class="nav-item {{ Request::routeIs('admin.invoices.*') || Request::routeIs('admin.invoice-payments.*') || Request::routeIs('admin.payment-methods.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::routeIs('admin.invoices.*') || Request::routeIs('admin.invoice-payments.*') || Request::routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            {{ __('Finance') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ Request::routeIs('admin.invoices.*') && !Request::routeIs('admin.invoice-payments.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Invoices') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payment-methods.index') }}" class="nav-link {{ Request::routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Payment Methods') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- SYSTEM SETTINGS -->
                @can('manage_settings')
                <li class="nav-header">{{ __('SYSTEM CONFIG') }}</li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ Request::routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>{{ __('General Settings') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.languages.index') }}" class="nav-link {{ Request::routeIs('admin.languages.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-language"></i>
                        <p>{{ __('Multi-language') }}</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item mt-3">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-admin">
                        @csrf
                        <a href="#" class="nav-link bg-danger" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ __('Sign Out') }}</p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>