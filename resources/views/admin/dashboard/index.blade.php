@extends('admin.layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <div>
        <div class="d-flex mb-4">
            <div class="form-group mr-2">
                <select id="filter-tahun" class="form-select select2" onchange="filterByYear()">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->hasRole('admin'))
                <div class="form-group">
                    <select id="filter-prodi" class="form-select select2" onchange="filterByProdi()">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- Cards Section -->
                @php
                    $cards = [
                        ['color' => 'primary', 'icon' => 'fas fa-book', 'title' => 'Total Buku Terdaftar', 'count' => $totalBooks, 'route' => route('pengajuan.index'), 'text' => 'Semua Pengajuan'],
                        ['color' => 'success', 'icon' => 'fas fa-check-circle', 'title' => 'Total Buku Diterima', 'count' => $acceptedBooks, 'route' => route('rekap-pengajuan.index'), 'text' => 'Jumlah buku diterima.'],
                        ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'title' => 'Total Buku Ditolak', 'count' => $rejectBooks, 'route' => route('pengajuan.tolak'), 'text' => 'Jumlah buku yang ditolak.'],
                        ['color' => 'warning', 'icon' => 'fas fa-clock', 'title' => 'Buku Pending', 'count' => $pendingBooks, 'route' => route('pengajuan.proses'), 'text' => 'Buku belum diproses.']
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card text-white bg-{{ $card['color'] }} shadow h-100">
                            <div class="card-header text-center">
                                <i class="{{ $card['icon'] }} fa-2x"></i>
                                <h5 class="mt-2">{{ $card['title'] }}</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="card-title font-weight-bold">{{ $card['count'] }}</h2>
                                <p class="card-text">{{ $card['text'] }}</p>
                                <a href="{{ $card['route'] }}" class="btn btn-light btn-sm">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <!-- Chart Section for Monthly Statistics -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">📊 Statistik Buku Bulanan (1 Tahun)</div>
                            <div class="card-body">
                                <canvas id="monthlyBooksChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->hasRole('admin'))
                <div class="col-6">
                    <!-- Chart Section for Books per Prodi -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header bg-secondary text-white">📈 Rekap Buku per Prodi/Unit</div>
                                <div class="card-body">
                                    <canvas id="booksPerProdiChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        .card-header i {
            display: block;
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Monthly Books Chart
            var monthlyCtx = document.getElementById('monthlyBooksChart').getContext('2d');
            var monthlyBooksChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    datasets: [{
                        label: 'Jumlah Buku',
                        data: @json(array_values($monthlyData)), // Monthly data from controller
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Books per Prodi Chart
            var prodiCtx = document.getElementById('booksPerProdiChart').getContext('2d');
            var booksPerProdiChart = new Chart(prodiCtx, {
                type: 'pie',
                data: {
                    labels: @json($booksPerProdi->pluck('nama')), // Labels with Prodi names
                    datasets: [{
                        label: 'Jumlah Buku per Prodi',
                        data: @json($booksPerProdi->pluck('total')), // Data for Prodi
                        backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8'],
                        borderColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });

        function filterByYear() {
            var year = document.getElementById('filter-tahun').value;
            var url = new URL(window.location.href);
            url.searchParams.set('year', year);
            window.location.href = url;
        }

        function filterByProdi() {
            var prodi = document.getElementById('filter-prodi').value;
            var url = new URL(window.location.href);
            url.searchParams.set('prodi', prodi);
            window.location.href = url;
        }
    </script>
@endpush
