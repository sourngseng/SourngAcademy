<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @auth
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('guest.dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/student/classes') }}" class="nav-link">My Classes</a>
        </li>
        @else
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">Home</a>
        </li>
        @endauth
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
                    <a href="{{ route(Auth::check() ? 'guest.language.switch' : 'language.switch', $locale) }}" class="dropdown-item {{ app()->getLocale() == $locale ? 'active' : '' }}">
                        <span style="font-size: 1.2em;">{{ $data['flag'] }}</span> {{ $data['name'] }}
                        @if(app()->getLocale() == $locale)
                            <i class="fas fa-check float-right text-success"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </li>
        
        <!-- User Dropdown -->
        @auth
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                @if(Auth::user()->profile_image)
                    <img src="{{ asset(Auth::user()->profile_image) }}" class="img-circle" alt="User Image" style="width: 25px; height: 25px; object-fit: cover;">
                @else
                    <i class="fas fa-user-circle"></i>
                @endif
                {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('guest.profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>
        @else
        <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </li>
        @endauth
    </ul>
</nav>
