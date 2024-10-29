<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('style-page')

    <style>
        /* Header and Footer */
        .header, .footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 500;
        }

        .footer {
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Body Style */
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .container {
            padding: 0;
        }

        /* Menu Navigation Styles */
        .menu-nav {
            background-color: #c4ced8; /* Warna latar belakang menu */
            padding: 5px 0; /* Padding atas dan bawah */
            width: 100%; /* Memastikan lebar sama dengan header */
        }

        .menu-nav .nav-link {
            color: #444c61; /* Warna teks */
            font-size: 12px; /* Ukuran font lebih kecil */
            padding: 8px 15px; /* Padding untuk tombol menu */
            border-radius: 0; /* Menghapus sudut membulat */
            transition: background-color 0.3s;
        }

        .menu-nav .nav-link:hover {
            background-color: #6db0d6; /* Warna saat hover */
        }

        /* Right-aligned styles */
        .menu-nav .navbar {
            justify-content: flex-end; /* Memastikan menu berada di kanan */
        }

        /* Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #003366;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e6f7ff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
            }
            .container-fluid, .container {
                padding: 0;
            }
        }

        .rekap-title {
            font-size: 36px;
            font-weight: bold;
            color: #2a5298;
            text-transform: uppercase;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <img src="{{ asset('image/img.png') }}" alt="UAD Logo">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <ul class="navbar-nav ml-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}" style="color: #f2f4f7;">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color: #f2f4f7;">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="dropdown-item-text">
                                    <strong>Name:</strong> {{ Auth::user()->name }}<br>
                                    <strong>Email:</strong> {{ Auth::user()->email }}<br>
                                    <strong>Role:</strong> {{ Auth::user()->roles->pluck('name')->first() }}<br>
                                    <strong>Prodi:</strong> {{ Auth::user()->prodi ? Auth::user()->prodi->nama : '-' }}
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

<!-- Menu Navigation Bar -->
<nav class="menu-nav">
    <div class="container">
        <ul class="nav nav-pills justify-content-end"> <!-- Align menu to the right -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Pengajuan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home-rekap') }}">Realisasi</a>
            </li>
        </ul>
    </div>
</nav>

<main class="py-4">
    <div class="container-fluid">
        <!-- Content Section -->
        @yield('content')
    </div>
</main>

<div class="footer">
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
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
