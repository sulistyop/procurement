@extends('user.layouts.app-user')

@section('title')
Realisasi
@endsection

@section('content')
    <div class="container-fluid my-4">
        <h1 class="rekap-title text-center">Rekap Realisasi</h1>
    </div>

    <form method="get" action="{{ route('home-rekap') }}">
        <div class="d-flex justify-content-between mb-2">
            <div class="d-flex">
                <!-- Filter Tanggal Awal -->
                <div class="form-group mr-2">
                    <label for="filter-start-date">Tanggal Awal:</label>
                    <input type="date" id="filter-start-date" class="form-control" name="start_date" value="{{ request('start_date', $startDate) }}">
                </div>
    
                <!-- Filter Tanggal Akhir -->
                <div class="form-group mr-2">
                    <label for="filter-end-date">Tanggal Akhir:</label>
                    <input type="date" id="filter-end-date" class="form-control" name="end_date" value="{{ request('end_date', $endDate) }}">
                </div>
    
                <!-- Filter Button -->
                <div class="form-group align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
    
            <!-- Export Button -->
            <div>
                <a type="button" class="btn btn-outline-info" href="{{ route('home-rekap', array_merge(request()->query(), ['export' => true])) }}">
                    Export Excel
                    <i class="fa fa-download"></i>
                </a>
            </div>
        </div>
    
        <!-- Search Field Below the Export Button -->
        <div class="mt-3">
            <div class="form-group">
                <label for="search-input">Pencarian:</label>
                <input type="text" id="search-input" class="form-control" name="search" placeholder="Cari berdasarkan judul, pengarang, ISBN" value="{{ request('search') }}">
            </div>
        </div>
    </form>
    
    @if($pengajuan->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            Tidak ada rekap yang ditemukan. Silakan pilih filter tanggal atau kata kunci pencarian yang lain.
        </div>
    @else
        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-striped mt-4" id="customers">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>Jumlah di Ajukan</th>
                        <th>Jumlah di Terima</th>
                        <th>Tahun Pengadaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->author }}</td>
                            <td>{{ $item->penerbit }}</td>
                            <td>{{ $item->tahun }}</td>
                            <td>{{ $item->eksemplar }}</td>
                            <td>{{ $item->diterima }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->approved_at)->format('Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pengajuan->links() }}
        </div>
    @endif

@endsection
