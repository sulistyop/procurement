@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <div>
        <div class="d-flex">
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
                    <label for="filter-prodi">Filter Prodi</label>
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
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-header text-center">Total Buku Terdaftar</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $totalBooks }}</h5>
                            <p class="card-text">Semua Pengajuan</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-header text-center">Total Buku Diterima</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $acceptedBooks }}</h5>
                            <p class="card-text">Jumlah buku diterima.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-white bg-danger">
                        <div class="card-header text-center">Total Buku Ditolak</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $rejectBooks }}</h5>
                            <p class="card-text">Jumlah buku yang ditolak.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-header text-center">Buku Pending</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $pendingBooks }}</h5>
                            <p class="card-text">Buku belum diproses.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-6">
                <!-- Chart Section for Monthly Statistics -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Statistik Buku Bulanan (1 Tahun)</div>
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
                        <div class="card">
                            <div class="card-header">Rekap Buku per Prodi/Unit</div>
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