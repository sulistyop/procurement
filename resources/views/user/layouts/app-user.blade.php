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

        /* Header Section */
        .header {
            background-color: #003366; /* Dark Blue */
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 500;
        }
        .form-group label {
            color: #FF5733; /* Matching orange label */
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            background-color: #003366; /* Dark Blue */
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<!-- Header Section -->
<div class="header">
    <div class="container-fluid">
        <div class=" d-flex justify-content-between align-items-center">
            <img src="{{ asset('image/img.png') }}" alt="UAD Logo">

            <nav class="navbar navbar-expand-md navbar-light shadow-sm">

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

                                <div class="dropdown-menu profile-dropdown-menu" style="min-width: 250px" aria-labelledby="navbarDropdown">
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
        </div>
    </div>
</div>

<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
</main>
<div class="footer">
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
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
