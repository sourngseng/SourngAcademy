<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('staff.dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('staff.bookings.arrivals') }}" class="nav-link">Today's Arrivals</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Current Date/Time -->
        <li class="nav-item">
            <span class="nav-link">
                <i class="fas fa-clock"></i> {{ now()->format('D, M d, Y') }}
            </span>
        </li>
        
        <!-- Language Switcher -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fas fa-language"></i>
                <span class="badge badge-info navbar-badge">{{ strtoupper(app()->getLocale()) }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-header">{{ __('Language') }}</span>
                @foreach(config('app.available_locales') as $locale => $data)
                    <a href="{{ route('staff.language.switch', $locale) }}" class="dropdown-item {{ app()->getLocale() == $locale ? 'active' : '' }}">
                        <span style="font-size: 1.2em;">{{ $data['flag'] }}</span> {{ $data['name'] }}
                        @if(app()->getLocale() == $locale)
                            <i class="fas fa-check float-right text-success"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </li>
        
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
