<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- User Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-size-50 img-circle" alt="User Image" style="width:30px;">
        <span class="hidden-xs">{{ strstr(Auth::user()->email, '@', true) }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
        <a href="{{ route('admin.profile') }}" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> {{ __('My Profile') }}
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.change_password') }}" class="dropdown-item">
          <i class="fas fa-lock mr-2"></i> {{ __('Change Password') }}
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->