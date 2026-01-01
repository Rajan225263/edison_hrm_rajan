<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title','Dashboard')</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- ================= Navbar ================= -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <!-- Left navbar -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>

            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('employees.index') }}" class="nav-link">
                    ERP Admin ( <b>Name:</b> Rajan Bhatta, <b>Email:</b> aobak63@gmail.com, <b>Mobile:</b> 01621-199769 )
                </a>
            </li>
        </ul>

        <!-- Right navbar -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    {{ auth()->user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item-text">
                        {{ auth()->user()->email }}
                    </span>

                    <div class="dropdown-divider"></div>

                    <a href="#"
                       class="dropdown-item"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>

                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST"
                          class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- ================= End Navbar ================= -->


    <!-- ================= Sidebar ================= -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="{{ route('employees.index') }}" class="brand-link text-center">
            <span class="brand-text font-weight-light">ERP Admin</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">

                    <li class="nav-item has-treeview {{ request()->routeIs('employees.*','departments.*','skills.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('employees.*','departments.*','skills.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                HRM
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('employees.index') }}"
                                   class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Employees</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('departments.index') }}"
                                   class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Departments</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('skills.index') }}"
                                   class="nav-link {{ request()->routeIs('skills.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Skills</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>
    <!-- ================= End Sidebar ================= -->


    <!-- ================= Content ================= -->
    <div class="content-wrapper">
        <section class="content pt-3">
            @yield('content')
        </section>
    </div>
    <!-- ================= End Content ================= -->


    <!-- ================= Footer ================= -->
    <footer class="main-footer text-center">
        <strong>&copy; {{ date('Y') }} ERP System</strong>
    </footer>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')

</body>
</html>
