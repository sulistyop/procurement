@extends('admin.layouts.app')

@section('title')
    Rekap Realisasi Pengajuan Buku yang Diterima
@endsection

@section('content')
    <div>
        <h1 class="mb-4 text-center text-primary font-weight-bold">
            REKAP RALISASI DITERIMA
        </h1>
        <hr class="my-4 border-top border-primary">
        
        <div class="d-flex justify-content-between mb-2">
            <div>
                <a type="button" class="btn btn-outline-info" href="{{ route('rekap-pengajuan.index', array_merge(request()->query(), ['export' => true])) }}">
                    Export Excel
                    <i class="fa fa-download"></i>
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <form method="get" action="{{ route('rekap-pengajuan.index') }}">
            <div class="d-flex justify-content-between mb-3">
                <!-- Filter Tanggal dan Prodi -->
                <div class="d-flex">
                    <div class="form-group mr-2">
                        <label for="filter-start-date">Tanggal Awal:</label>
                        <input type="date" id="filter-start-date" name="start_date" class="form-control" value="{{ request('start_date', \Carbon\Carbon::today()->toDateString()) }}">
                    </div>
                    <div class="form-group mr-2">
                        <label for="filter-end-date">Tanggal Akhir:</label>
                        <input type="date" id="filter-end-date" name="end_date" class="form-control" value="{{ request('end_date', \Carbon\Carbon::today()->toDateString()) }}">
                    </div>
                    <div class="form-group mr-2">
                        <label for="filter-prodi">Prodi:</label>
                        <select id="filter-prodi" name="prodi" class="form-control">
                            <option value="">Semua Prodi</option>
                            @foreach($prodis as $p)
                                <option value="{{ $p->id }}" {{ request('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <table class="table mt-4" id="customers">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Prodi</th>
                    <th>ISBN</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Jumlah di Ajukan</th>
                    <th>Jumlah di Terima</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->prodi->nama }}</td>
                        <td>{{ $item->isbn }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->author }}</td>
                        <td>{{ $item->penerbit }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ $item->eksemplar }}</td>
                        <td>{{ $item->diterima }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data pengajuan yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $pengajuan->links() }}
        </div>

    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#customers').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
@endpush
