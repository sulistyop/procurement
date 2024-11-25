@extends('admin.layouts.app')

@section('title')
Realisasi
@endsection

@section('content')
    <div>
        <h1>Rekap Realisasi</h1>

        <div class="d-flex justify-content-between mb-2">
            <div>
                <a type="button" class="btn btn-outline-info" href="{{ route('rekap-pengajuan.index', array_merge(request()->query(), ['export' => true])) }}">
                    Export Excel
                    <i class="fa fa-download"></i>
                </a>
            </div>

            <div class="d-flex">
                <div class="form-group mr-2">
                    <label for="filter-start-date">Tanggal Awal:</label>
                    <input type="date" id="filter-start-date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="form-group mr-2">
                    <label for="filter-end-date">Tanggal Akhir:</label>
                    <input type="date" id="filter-end-date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="form-group align-self-end">
                    <button type="button" class="btn btn-primary" onclick="filterByDate()">Filter</button>
                </div>
            </div>
        </div>

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
                        <td colspan="10" class="text-center">Tidak ada data pengajuan yang ditemukan. Silakan pilih rentang tanggal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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

        function filterByDate() {
            var startDate = $('#filter-start-date').val();
            var endDate = $('#filter-end-date').val();
            var url = new URL(window.location.href);

            if (startDate) {
                url.searchParams.set('start_date', startDate);
            } else {
                url.searchParams.delete('start_date');
            }

            if (endDate) {
                url.searchParams.set('end_date', endDate);
            } else {
                url.searchParams.delete('end_date');
            }

            window.location.href = url; // Reload halaman dengan parameter baru
        }
    </script>
@endpush
