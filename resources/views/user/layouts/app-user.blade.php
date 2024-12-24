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
        /* Global Layout */
        /* html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        } */
/* 
        main {
            flex: 1;
        } */
        Reset CSS
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Pastikan padding tidak mempengaruhi ukuran elemen */
        }

        /* Global Layout */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-image: url('{{ asset('image/bg.png') }}'); /* Tambahkan gambar sebagai background */
            background-size: cover; /* Menyesuaikan gambar agar memenuhi layar */
            background-repeat: no-repeat;
            background-attachment: fixed; /* Membuat background tetap saat scrolling */
            font-family: 'Arial', sans-serif;
            color: #333;
            display: flex;
            flex-direction: column;
        }

        /* Main Content */
        main {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.9); /* Transparansi untuk memperjelas konten */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px; /* Padding untuk memberi jarak pada konten */
            margin: 40px auto; /* Memberi margin atas dan bawah */
            max-width: 1800px; /* Membatasi lebar maksimum */
            width: 95%; /* Lebar responsif untuk perangkat kecil */
        }

        /* Footer */
        .footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 2px 2px;
            font-size: 14px;
            border-top: 3px solid #002244;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                margin: 20px auto;
                padding: 15px;
                width: 100%; /* Memaksimalkan lebar pada perangkat kecil */
                border-radius: 5px;
            }

            .footer {
                font-size: 12px; /* Mengecilkan ukuran font footer */
                padding: 10px 5px;
            }
        }
        .navbar {
            background-color: #003366;
            }

        /* Header and Footer */
        .header {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 2px;
        }

        .header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 500;
        }
        /* 
        .footer {
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
            background-color: #003366;
            color: white;
            font-size: 14px;
            border-top: 3px solid #003366;
        } */

        /* Menu Navigation Styles */
        .menu-nav {
            background-color: #c4ced8;
            margin: 0;
            padding: 5px;
            width: 100%;
            margin-top: 0;
            background-image: url('{{ asset('image/menu.png') }}'); 
            background-size: cover; 
            background-repeat: no-repeat; 
            background-position: center;
        }

        .menu-nav .nav-link {
            margin: 0;
            /* padding: 1px 1px; */
            padding: 0 1px;
            font-size: 14px;
            color: #444c61;
            transition: color 0.3s;
            letter-spacing: 0.5px;
        }
        .menu-nav .nav-item {
            margin-left: 20px; /* Memberikan jarak antar menu */
        }

        .menu-nav .nav-pills {
            margin-bottom: 0; /* Menghilangkan jarak bawah pada ul */
        }

        .menu-nav .nav-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .menu-nav .nav-link.active {
            color: #003366;
            font-weight: bold;
        }

        .menu-nav .navbar {
            justify-content: flex-end;
        }
        .menu-nav .nav-pills {
            margin: 1px; 
            padding: 1px; 
            letter-spacing: 0.5px;
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
            vertical-align: middle;
            text-align: left;
            padding: 12px;
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
        }

        .rekap-title {
            font-size: 36px;
            font-weight: bold;
            color: #2a5298;
            text-transform: uppercase;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        /* Tombol Simpan */
        .btn-save {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 30px;
            border: none;
            background-color: #28a745;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Tombol Kembali */
        .btn-back {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 30px;
            border: none;
            background-color: #6c757d;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn-save i, .btn-back i {
            margin-right: 8px;
        }
    </style>
     @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <img src="{{ asset('image/img.png') }}" alt="UAD Logo">
        <nav class="navbar navbar-expand-md  shadow-sm">
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
        <ul class="nav nav-pills justify-content-end">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('welcome') }}">
                    <i class="fas fa-file-alt"></i> Pengajuan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home-rekap') }}">
                    <i class="fas fa-chart-line"></i> Realisasi
                </a>
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
    <p> &copy;ASP 2024 . Perpustakaan Universitas Ahmad Dahlan.</p>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

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
