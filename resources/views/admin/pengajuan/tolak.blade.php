@extends('admin.layouts.app')

@section('title')
    Pengajuan
@endsection

@push('style-page')
    <style>
        .custom-select {
            width: 70px !important;
        }
    </style>
@endpush

@section('content')
    <div>
        <h1 class="mb-4 text-center text-primary font-weight-bold">
           REKAP PENGAJUAN BUKU DITOLAK
        </h1>
        <hr class="my-4 border-top border-primary">
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="mb-2">
            <a type="button" class="btn btn-outline-info" href="{{ route('pengajuan.tolak').'?export=true' }}">
                Download Excel
                <i class="fa fa-download"></i>
            </a>
        </div>

        <!-- Filter Section -->
        <form method="get" action="{{ route('pengajuan.tolak') }}">
            <div class="d-flex justify-content-between mb-3">
                <!-- Filter Tanggal -->
                <div class="d-flex">
                    <div class="form-group mr-2">
                        <label for="from_date">Dari Tanggal:</label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group mr-2">
                        <label for="to_date">Sampai Tanggal:</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                </div>
                <!-- Filter Prodi -->
                <div class="d-flex">
                    <div class="form-group mr-2">
                        <label for="filter-prodi">Prodi:</label>
                        <select id="filter-prodi" name="prodi" class="form-control" onchange="filterByProdi()">
                            <option value="">Pilih Prodi</option>
                            @foreach($prodi as $prodiItem)
                                <option value="{{ $prodiItem->id }}" {{ request('prodi') == $prodiItem->id ? 'selected' : '' }}>{{ $prodiItem->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Filter Button -->
                    <div class="form-group align-self-end">
                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Table -->
        <table class="table mt-4" id="customers">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Prodi</th>
                    <th>ISBN</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->prodi->nama }}</td>
                        <td>
                            {{-- Data ini pernah diajukan di tahun --}}
                            @if($item->is_diajukan)
                                {{ $item->isbn }} <span class="badge badge-info">Pernah diajukan tahun  {{ \Illuminate\Support\Carbon::parse($item->date_pernah_diajukan)->format('d-m-Y') }}</span>
                            @else
                                {{ $item->isbn }}
                            @endif
                        </td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->author }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}</td>
                        <td>
                            @if($item->is_approve)
                                <span class="badge badge-success">Disetujui Perpustakaan</span>
                            @elseif($item->is_reject)
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Proses</span>
                            @endif
                        </td>
                        <td>
                            @can('view pengajuan')
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="btn btn-info btn-sm"
                                   data-toggle="tooltip" data-placement="top" title="View">
                                     <i class="fas fa-binoculars"></i>
                                </a>
                            @endcan
                            @if(!$item->is_approve && !$item->is_reject)
                                @can('edit pengajuan')
                                    <a href="#" class="btn btn-warning btn-sm" data-toggle="modal"
                                       data-target="#editPengajuanModal" data-id="{{ $item->id }}"
                                       data-prodi="{{ $item->prodi->id }}" data-judul="{{ $item->judul }}"
                                       data-edisi="{{ $item->edisi }}" data-isbn="{{ $item->isbn }}"
                                       data-penerbit="{{ $item->penerbit }}" data-author="{{ $item->author }}"
                                       data-tahun="{{ $item->tahun }}" data-eksemplar="{{ $item->eksemplar }}"
                                       data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete pengajuan')
                                    <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip"
                                                data-placement="top" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endcan
                                @can('approve pengajuan')
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#approveModal" data-all="{{ $item }}" data-id="{{ $item->id }}"
                                            data-jumlah="{{ $item->eksemplar }}" data-toggle="tooltip" data-placement="top"
                                            title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $pengajuan->links() }}
        </div>

        @include('admin.component.edit-modal')
        @include('admin.component.tambah-pengajuan-modal')
        @include('admin.component.approve-modal')
        @include('admin.component.upload-modal')
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#customers').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });

        $('#approveModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var allData = button.data('all');
            var modal = $(this);
            var form = modal.find('#approveForm');
            var actionUrl = '{{ route('pengajuan.storeApproval', ':id') }}'.replace(':id', id);
            form.attr('action', actionUrl);

            modal.find('.modal-body #nama_prodi').val(allData.nama_prodi);
            modal.find('.modal-body #isbn').val(allData.isbn);
            modal.find('.modal-body #judul').val(allData.judul);
            modal.find('.modal-body #tahun').val(allData.tahun);
            modal.find('.modal-body #penulis').val(allData.author);
            modal.find('.modal-body #eksemplar').val(allData.eksemplar);
        });
    </script>
@endpush
