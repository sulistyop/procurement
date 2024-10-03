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

    {{--cdn fa--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Add this in your Blade template, preferably in the head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    @stack('style-page')
    <style>
        /* Tambahkan CSS ini ke style.css atau dalam tag <style> di file blade Anda */
        #sidebar {
            width: 250px;
            transition: margin-left 0.3s ease;
        }

        .custom-select{
            width: 70px !important;
        }

        #sidebar.active {
            margin-left: -220px; /* Menyisakan ruang untuk ikon */
        }

        #sidebar .nav-link {
            display: flex;
            align-items: center;
        }

        #sidebar .nav-link .fas {
            margin-right: 10px;
        }

        #sidebar.active .nav-link span {
            display: none; /* Sembunyikan teks saat sidebar disembunyikan */
        }

        #app {
            transition: margin-left 0.3s ease;
        }

        .nav-link.active {
            background-color: #007bff; /* Change this to your desired active background color */
            color: white; /* Ensure the text color is readable */
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="d-flex">
        <!-- Sidebar -->
        <div class="text-white sidebar p-3" id="sidebar">
            <div class="logo-container">
                <a href="#" class="logo">
                    <img src="{{ asset('image/perpus2.png') }}" alt="Logo" class="logo-img">
                </a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                @can('manage pengajuan')
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('pengajuan') ? 'active' : '' }}" href="{{ url('/pengajuan') }}">
                            <i class="fas fa-file-alt"></i> Data Pengajuan
                        </a>
                    </li>
                @endcan
                @can('manage rekap pengajuan')
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is('rekap-pengajuan') ? 'active' : '' }}" href="{{ route('rekap-pengajuan.index') }}">
                            <i class="fas fa-table"></i> Rekap Realisasi
                        </a>
                    </li>
                @endcan
                @can('manage users')
                    <a class="nav-link text-white {{ Request::is('user*') || Request::is('roles-permissions*') ? ' ' : '' }}" href="#userMenu" data-toggle="collapse" aria-expanded="false" aria-controls="userMenu">
                        <i class="fas fa-user"></i> Pengguna
                        <i class="fas fa-chevron-down ml-auto" id="userMenuIcon"></i>
                    </a>
                    <div class="collapse {{ Request::is('user*') || Request::is('roles-permissions*') ? 'show' : '' }}" id="userMenu">
                        <a class="nav-link text-white pl-4 {{ Request::is('user') ? 'active' : '' }}" href="{{ route('user.index') }}">List Pengguna</a>
                        <a class="nav-link text-white pl-4 {{ Request::is('roles-permissions*') ? 'active' : '' }}" href="{{ route('roles-permissions.edit') }}">Izin</a>
                    </div>
                @endcan
               {{-- <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('settings') ? 'active' : '' }}" href="#">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>--}}
               {{--buatkan menu activity log--}}
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('activity-logs') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">
                        <i class="fas fa-history"></i> Activity Logs
                    </a>
                </li>

            </ul>
        </div>


        <!-- Main content -->
        <div class="content flex-grow-1" id="main-content">
            <nav class="navbar navbar-expand-md navbar-light shadow-sm">
                <button id="sidebarToggle" class="btn btn-primary ml-2" style="background-color: #0e2742;">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="container">
                    <ul class="navbar-nav ml-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}" style="color: #f2f4f7;" >{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color: #f2f4f7;">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu" style="min-width: 250px" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-item-text">
                                        <strong>Name:</strong> {{ Auth::user()->name }}<br>
                                        <strong>Email:</strong> {{ Auth::user()->email }}<br>
                                        <strong>Role:</strong> {{ Auth::user()->roles->pluck('name')->first() }}<br>
                                        <strong>Prodi:</strong> {{ Auth::user()->prodi ? Auth::user()->prodi->nama : '-' }}
                                    </div>
                                    <div class="dropdown-divider"></div>
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
                <div class="p-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
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
            const app = document.getElementById('app');

            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('active');

                // Mengubah margin konten berdasarkan status sidebar
                if (sidebar.classList.contains('active')) {
                    app.style.marginLeft = '0px'; // Konten tanpa margin saat sidebar tertutup
                } else {
                    app.style.marginLeft = '0px'; // Mengembalikan margin saat sidebar terbuka
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const userMenu = document.getElementById('userMenu');
            const userMenuIcon = document.getElementById('userMenuIcon');

            $('#userMenu').on('show.bs.collapse', function () {
                userMenuIcon.classList.remove('fa-chevron-down');
                userMenuIcon.classList.add('fa-chevron-up');
            });

            $('#userMenu').on('hide.bs.collapse', function () {
                userMenuIcon.classList.remove('fa-chevron-up');
                userMenuIcon.classList.add('fa-chevron-down');
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 on all select elements with the class 'select2'
            $('.select2').select2();
        });
    </script>
    <!-- Add this in your Blade template, preferably in the head section -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend(true, $.fn.dataTable.defaults, {

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
                }
            });
        });
    </script>
    @stack('script-page')

</body>
</html>
