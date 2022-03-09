<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Админ-панель</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">

</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ route('user.logout') }}" class="nav-link">Выйти</a>
          </li>
      </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.home') }}" class="brand-link" target="_blank">
      <img src="{{ asset('assets/admin/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">На сайт</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/admin/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Поиск" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('admin.home') }}" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>Главная</p>
            </a>
          </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-link"></i>
                    <p>
                        Ссылки
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.links.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Список ссылок</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.links.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Добавить ссылку</p>
                        </a>
                    </li>
                </ul>
            </li>
            @if( Auth::user()->is_admin)
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cloud"></i>
                    <p>
                        Домены
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.domains.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Список доменов</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.domains.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Добавить домен</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-signal"></i>
                    <p>
                        Статистика
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.stats') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Статистика по дням</p>
                        </a>
                    </li>
                    @if( !Auth::user()->is_admin)
                    <li class="nav-item">
                        <a href="{{ route('admin.stats.user.week') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Статистика за неделю</p>
                        </a>
                    </li>
                    @endif
                    @if( Auth::user()->is_admin)
                    <li class="nav-item">
                        <a href="{{ route('admin.stats.user') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Статистика по пользователю</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @if( Auth::user()->is_admin)
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Пользователи
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Список пользователей</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Добавить пользователя</p>
                        </a>
                    </li>
                </ul>
            </li>
                @if( Auth::user()->is_admin)
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Настройки</p>
                    </a>
                </li>
                @endif
            @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
  <!-- Content Wrapper. Contains page content -->
  @if ($errors->any())
  <div class="container-fluid mt-2">
      <div class="row">
          <div class="col-12">
            <div class="alert alert-danger">
                <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
          </div>
      </div>
  </div>
  @endif

  @if (session()->has('success'))
  <div class="container-fluid mt-2">
      <div class="row">
          <div class="col-12">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          </div>
      </div>
  </div>
  @endif

    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
      <strong>Copyright &copy; 2021 All rights reserved.</strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="{{ asset('assets/admin/js/admin.js') }}"></script>

<script>
    $('.nav-sidebar a').each(function(){
        let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
        let link = this.href;
        if (link == location) {
            $(this).addClass('active');
            $(this).parents('.nav-item').addClass('menu-open');
        }
    });
</script>

</body>
</html>
