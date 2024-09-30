@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <div>
        <div class="container-fluid"> <!-- Gunakan container-fluid untuk mengisi ruang yang tersedia -->
            <div class="row">
                @role('admin')
                <!-- Kartu Total Buku Terdaftar Hanya untuk Admin -->
                <div class="col-md-3 col-sm-6 mb-3"> <!-- Tambahkan col-sm-6 untuk responsivitas di layar kecil -->
                    <div class="card text-white bg-primary" style="max-height: 250px;">
                        <div class="card-header text-center">Total Buku Terdaftar</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $totalBooks }}</h5>
                            <p class="card-text">Rekap Diterima.</p>
                            <a href="{{ route('rekap-pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                @endrole
        
                <!-- Kartu Total Buku Diterima (Bisa diakses semua peran) -->
                <div class="col-md-3 col-sm-6 mb-3"> <!-- Tambahkan col-sm-6 -->
                    <div class="card text-white bg-success" style="max-height: 250px;">
                        <div class="card-header text-center">Total Buku Diterima</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $acceptedBooks }}</h5>
                            <p class="card-text">Jumlah buku diterima.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Kartu Buku Ditolak -->
                <div class="col-md-3 col-sm-6 mb-3"> <!-- Tambahkan col-sm-6 -->
                    <div class="card text-white bg-danger" style="max-height: 250px;">
                        <div class="card-header text-center">Total Buku Ditolak</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $rejectBooks }}</h5>
                            <p class="card-text">Jumlah buku yang ditolak.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
        
                <!-- Kartu Buku Pending -->
                <div class="col-md-3 col-sm-6 mb-3"> <!-- Tambahkan col-sm-6 -->
                    <div class="card text-white bg-warning" style="max-height: 250px;">
                        <div class="card-header text-center">Buku Pending</div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $pendingBooks }}</h5>
                            <p class="card-text">Buku belum proses.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        

        <!-- Chart Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Statistik Buku</div>
                    <div class="card-body">
                        <canvas id="booksChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('booksChart').getContext('2d');
            var booksChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Buku Terdaftar', 'Buku Ditolak', 'Buku Pending'],
                    datasets: [{
                        label: 'Jumlah Buku',
                        data: [{{ $totalBooks }}, {{ $rejectBooks }}, {{ $pendingBooks }}],
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107'], // Warna untuk setiap kategori
                        borderColor: ['#28a745', '#dc3545', '#ffc107'], // Warna border sesuai
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Statistik Buku',
                            font: {
                                size: 18
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
