@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>
    <!-- Filter Section -->
    <div class="d-flex mb-4 justify-content-between align-items-center">
        <div class="form-group mr-2">
            Tahun
            <select id="filter-tahun" class="form-select filter-input select2" onchange="filterByYear()">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        @if(auth()->user()->hasRole('admin'))
        <div class="form-group">
            Prodi/Unit
            <select id="filter-prodi" class="form-select filter-input select2" onchange="filterByProdi()">
                <option value="">Semua Prodi</option>
                @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    <!-- Card Section -->
    <div class="container-fluid">
        <div class="row">
            @php
                $cards = [
                    ['color' => 'linear-gradient(to right, #007bff, #004085)', 'icon' => 'fas fa-book', 'count' => $totalBooks, 'route' => route('pengajuan.index'), 'text' => 'Semua Pengajuan'],
                    ['color' => 'linear-gradient(to right, #28a745, #155724)', 'icon' => 'fas fa-check-circle', 'count' => $acceptedBooks, 'route' => route('rekap-pengajuan.index'), 'text' => 'Judul Diterima'],
                    ['color' => 'linear-gradient(to right, #dc3545, #721c24)', 'icon' => 'fas fa-times-circle', 'count' => $rejectBooks, 'route' => route('pengajuan.tolak'), 'text' => 'Judul Ditolak'],
                    ['color' => 'linear-gradient(to right, #ffc107, #d39e00)', 'icon' => 'fas fa-clock', 'count' => $pendingBooks, 'route' => route('pengajuan.proses'), 'text' => 'Buku Belum Diproses']
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card shadow-lg text-white h-100" style="background: {{ $card['color'] }}; border-radius: 12px; overflow: hidden;">
                        <div class="card-header text-center" style="font-size: 2rem;">
                            <i class="{{ $card['icon'] }} fa-2x"></i>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="card-title font-weight-bold mb-0">{{ $card['count'] }}</h2>
                            <p class="card-text">{{ $card['text'] }}</p>
                        </div>
                        <div class="card-footer text-center bg-light">
                            <a href="{{ $card['route'] }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header text-white" style="background: #17a2b8; font-weight: bold;">📊 Statistik Buku Bulanan</div>
                <div class="card-body bg-light">
                    <canvas id="monthlyBooksChart"></canvas>
                </div>
            </div>
        </div>
        @if(auth()->user()->hasRole('admin'))
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header text-white" style="background: #6c757d; font-weight: bold;">📈 Buku per Prodi/Unit</div>
                <div class="card-body bg-light">
                    <canvas id="booksPerProdiChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('style')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .filter-input {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .filter-input:hover, .filter-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }
</style>
@endpush

@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Monthly Books Chart
        var ctx1 = document.getElementById('monthlyBooksChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Jumlah Buku',
                    data: @json(array_values($monthlyData)),
                    backgroundColor: '#17a2b8',
                    borderRadius: 5
                }]
            },
            options: { responsive: true }
        });

        // Books per Prodi Chart
        var ctx2 = document.getElementById('booksPerProdiChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: @json($booksPerProdi->pluck('nama')),
                datasets: [{
                    data: @json($booksPerProdi->pluck('total')),
                    backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8']
                }]
            },
            options: { responsive: true }
        });
    });
    function filterByYear() {
        const year = document.getElementById('filter-tahun').value;
        const prodi = document.getElementById('filter-prodi').value;
        const url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        if (year) {
            params.set('year', year);
        } else {
            params.delete('year');
        }

        if (prodi) {
            params.set('prodi', prodi);
        } else {
            params.delete('prodi');
        }

        url.search = params.toString(); // Update URL
        window.location.href = url.toString(); // Reload page with updated parameters
    }

    function filterByProdi() {
        const prodi = document.getElementById('filter-prodi').value;
        const year = document.getElementById('filter-tahun').value;
        const url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        if (prodi) {
            params.set('prodi', prodi);
        } else {
            params.delete('prodi');
        }

        if (year) {
            params.set('year', year);
        } else {
            params.delete('year');
        }

        url.search = params.toString(); // Update URL
        window.location.href = url.toString(); // Reload page with updated parameters
    }

</script>
@endpush
