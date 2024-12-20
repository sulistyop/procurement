<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @stack('style-page')
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" id="sidebar">
            <div class="logo-container">
                <a href="#" class="logo">
                    <img src="{{ asset('image/perpus2.png') }}" alt="Logo" class="logo-img">
                </a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                @can('manage pengajuan')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('parent-pengajuan') ? 'active' : '' }}" href="{{ url('/parent-pengajuan') }}">
                            <i class="fas fa-file-alt"></i> Data Pengajuan
                        </a>
                    </li>
                @endcan
                @can('manage rekap pengajuan')
                    <a class="nav-link {{ Request::is('rekap-pengajuan') || Request::is('pengajuan-ditolak') ? '' : '' }}" href="#rekapMenu" data-toggle="collapse" aria-expanded="false" aria-controls="rekapMenu">
                        <i class="fas fa-chart-bar"></i> Realisasi Pengajuan
                        <i class="fas fa-chevron-down ml-auto" id="rekapMenuIcon"></i>
                    </a>
                    <div class="collapse {{ Request::is('rekap-pengajuan') || Request::is('pengajuan-ditolak') ? 'show' : '' }}" id="rekapMenu">
                        <a class="nav-link pl-4 {{ Request::is('rekap-pengajuan') ? 'active' : '' }}" href="{{ route('rekap-pengajuan.index') }}">
                            <i class="fas fa-check-circle"></i> Pengajuan Diterima
                        </a>
                        <a class="nav-link pl-4 {{ Request::is('pengajuan-ditolak') ? 'active' : '' }}" href="{{ route('pengajuan.tolak') }}">
                            <i class="fas fa-times-circle"></i> Pengajuan Ditolak
                        </a>
                    </div>
                @endcan
                @can('manage approve keuangan')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('approve-keuangan') ? 'active' : '' }}" href="{{ route('approve-keuangan.index') }}">
                            <i class="fas fa-coins"></i> Bukti Approve Keuangan
                        </a>
                    </li>
                @endcan
                @can('manage users')
                    <a class="nav-link {{ Request::is('user*') || Request::is('roles-permissions*') ? '' : '' }}" href="#userMenu" data-toggle="collapse" aria-expanded="false" aria-controls="userMenu">
                        <i class="fas fa-users"></i> Pengguna
                        <i class="fas fa-chevron-down ml-auto" id="userMenuIcon"></i>
                    </a>
                    <div class="collapse {{ Request::is('user*') || Request::is('roles-permissions*') ? 'show' : '' }}" id="userMenu">
                        <a class="nav-link pl-4 {{ Request::is('user') ? 'active' : '' }}" href="{{ route('user.index') }}">
                            <i class="fas fa-user"></i> List Pengguna
                        </a>
                        <a class="nav-link pl-4 {{ Request::is('roles-permissions*') ? 'active' : '' }}" href="{{ route('roles-permissions.edit') }}">
                            <i class="fas fa-user-shield"></i> Izin
                        </a>
                    </div>
                @endcan
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('activity-logs') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">
                        <i class="fas fa-history"></i> Activity Logs
                    </a>
                </li>
            </ul>
            <div class="copyright text-center mt-4">
                <p>ASP &copy; 2024. Perpustakaan UAD</p>
            </div>
        </div>

        <!-- Main content -->
        <div class="content flex-grow-1" id="main-content">
            <nav class="navbar navbar-expand-md navbar-light ">
                <button id="sidebarToggle" class="btn btn-primary ml-2" >
                    <i class="fas fa-bars"></i>
                </button>
                <div class="container">
                    <ul class="navbar-nav ml-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>

            <main class="py-4">
                <div class="p-4 box">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(document).ready(function() {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebarToggle');

            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        });

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend(true, $.fn.dataTable.defaults, {

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
                }
            });
        });
        $(document).ready(function() {
            $('#customers tbody').on('click', 'tr', function() {
                $('#customers tr').removeClass('highlighted-row');
                $(this).addClass('highlighted-row');
            });
        });
    </script>
    @stack('script-page')
    
</body>
</html>
