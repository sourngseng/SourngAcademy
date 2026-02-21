<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('teacher.dashboard') }}" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Dark Mode Toggle -->
    <li class="nav-item">
      <a class="nav-link" href="#" role="button" id="darkModeToggle">
        <i class="fas fa-moon"></i>
      </a>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
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
          <a href="{{ route('language.switch', $locale) }}" class="dropdown-item {{ app()->getLocale() == $locale ? 'active' : '' }}">
            <span style="font-size: 1.2em;">{{ $data['flag'] }}</span> {{ $data['name'] }}
            @if(app()->getLocale() == $locale)
              <i class="fas fa-check float-right text-success"></i>
            @endif
          </a>
        @endforeach
      </div>
    </li>

    <!-- User Dropdown Menu -->
    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        @if(Auth::user()->profile_image)
            <img src="{{ asset(Auth::user()->profile_image) }}" class="user-image img-circle elevation-2" alt="User Image">
        @else
            <img src="{{ asset('backend/dist/img/default-avatar.png') }}" class="user-image img-circle elevation-2" alt="User Image">
        @endif
        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class="user-header bg-primary">
          @if(Auth::user()->profile_image)
              <img src="{{ asset(Auth::user()->profile_image) }}" class="img-circle elevation-2" alt="User Image">
          @else
              <img src="{{ asset('backend/dist/img/default-avatar.png') }}" class="img-circle elevation-2" alt="User Image">
          @endif
          <p>
            {{ Auth::user()->name }}
            <small>{{ __('Member since') }} {{ Auth::user()->created_at->format('M. Y') }}</small>
          </p>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
          <a href="{{ route('teacher.profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline float-right">
              @csrf
              <button type="submit" class="btn btn-default btn-flat">Sign out</button>
          </form>
        </li>
      </ul>
    </li>
  </ul>
</nav>
