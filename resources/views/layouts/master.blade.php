
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Barera Fabrics</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="/css/app.css">

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
     <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 p">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="./img/logo.png" alt="Laravel Vue Js" class="brand-image img-circle elevation-3"
           style="opacity: 1;" id="logoimage">
      <span class="brand-text font-weight-light">Barera Fabrics</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/person.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth()->user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <router-link to="/garment/home" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <router-link to="/garment/invoice" class="nav-link">
              <i class="nav-icon fas fa-money-bill-wave"></i>
              <p>
                Invoice
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <router-link to="/garment/client" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Clients
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <router-link to="/garment/color" class="nav-link">
              <i class="nav-icon fas fa-palette"></i>
              <p>
                Colors
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <router-link to="/garment/product" class="nav-link">
              <i class="nav-icon fas fa-tshirt"></i>
              <p>
                Products
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <router-link to="/garment/users" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              <i class=" nav-icon fas fa-power-off red"></i>
              <p>
                {{ __('Logout') }}
              </p>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">

    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <a href="#">Core2Plus</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="/js/app.js"></script>
</body>
</html>
