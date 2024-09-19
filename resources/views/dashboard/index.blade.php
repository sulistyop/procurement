@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <!-- Kartu Total Buku Terdaftar -->
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Buku Terdaftar</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalBooks }}</h5>
                        <p class="card-text">Jumlah buku yang terdaftar dan disetujui.</p>
                        <a href="{{ route('rekap-pengajuan.index') }}" class="btn btn-light">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Kartu Buku Pending -->
            <div class="col-md-6">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Buku Pending</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $pendingBooks }}</h5>
                        <p class="card-text">Jumlah buku yang sedang menunggu persetujuan.</p>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-light">Lihat Detail</a>
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
                    labels: ['Total Buku Terdaftar', 'Buku Pending'],
                    datasets: [{
                        label: 'Jumlah Buku',
                        data: [{{ $totalBooks }}, {{ $pendingBooks }}],
                        backgroundColor: ['#28a745', '#ffc107'],
                        borderColor: ['#28a745', '#ffc107'],
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