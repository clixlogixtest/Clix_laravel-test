<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ url('/') }}" class="brand-link">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ strstr(Auth::user()->email, '@', true) }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ set_active('admin/dashboard') }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              {{ __('Dashboard') }}
            </p>
          </a>
        </li>

        <li class="nav-item {{ set_active(['admin/packages*'],'menu-open') }}">
          <a href="javascript:void(0)" class="nav-link {{ set_active(['admin/packages*']) }}">
            <i class="nav-icon fas fa-table"></i>
            <p>
              {{ __('Packages') }}
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.packages.index') }}" class="nav-link {{ set_active('admin/packages') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Package List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.packages.create') }}" class="nav-link {{ set_active('admin/packages/create') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Package</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item {{ set_active(['admin/addon_services*'],'menu-open') }}">
          <a href="javascript:void(0)" class="nav-link {{ set_active(['admin/addon_services*']) }}">
            <i class="nav-icon fas fa-table"></i>
            <p>
              {{ __('Addon Services') }}
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.addon_services.index') }}" class="nav-link {{ set_active('admin/addon_services') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Addon Service List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.addon_services.create') }}" class="nav-link {{ set_active('admin/addon_services/create') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Addon Service</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item {{ set_active(['admin/faqs*'],'menu-open') }} {{ set_active(['admin/pages*'],'menu-open') }}">
          <a href="javascript:void(0)" class="nav-link {{ set_active(['admin/faqs*']) }} {{ set_active(['admin/pages*']) }}">
            <i class="nav-icon fas fa-table"></i>
            <p>
              {{ __('Pages') }}
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.pages.index') }}" class="nav-link {{ set_active('admin/pages') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Page List</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ set_active('admin/faqs') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>FAQ List</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              {{ __('Logout') }}
            </p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>