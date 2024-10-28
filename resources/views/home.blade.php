@extends('user.layouts.app-user')

@section('title')
    Pengajuan
@endsection

@push('style-page')
    <style>
        /* Body Style */
        body {
            background-color: #f4f7fa; /* Light background */
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        /* Header Style */
        h1 {
            color: #003366; /* Dark Blue */
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        /* Button Styles */
        .btn-outline-primary, .btn-outline-info {
            border-radius: 25px; /* Rounded buttons */
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover, .btn-outline-info:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        /* Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            background-color: white;
        }

        th, td {
            text-align: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #003366; /* Dark Blue */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light grey */
        }

        tr:hover {
            background-color: #e6f7ff; /* Light blue on hover */
        }

        /* Alert Styles */
        .alert {
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            padding: 20px;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
            }
            .custom-select {
                width: auto !important;
            }
        }
    </style>
@endpush


@section('content')
    <div>
        <h1>Daftar Pengajuan</h1>
        @if(session('import_errors'))
            <div class="alert alert-danger">
                <ul>
                    @foreach(session('import_errors') as $failure)
                        <li>{{ $failure->errors()[0] }} di baris {{ $failure->row() }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-2">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#tambahPengajuanModal">
                Tambah Pengajuan
                <i class="fas fa-plus"></i>
            </button>
            <a type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#uploadModal">
                Upload Excel
                <i class="fa fa-upload"></i>
            </a>
            <a type="button" class="btn btn-outline-info" href="{{ route('pengajuan.index').'?export=true' }}">
                Export Excel
                <i class="fa fa-download"></i>
            </a>
        </div>

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
                        @if($item->is_diajukan)
                            {{ $item->isbn }} <span class="badge badge-info">Pernah diajukan tahun  {{ \Illuminate\Support\Carbon::parse($item->date_pernah_diajukan)->format('d-m-Y')  }}</span>
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
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('home-show', $item->id) }}" class="btn btn-info btn-sm mx-1"
                               data-toggle="tooltip" data-placement="top" title="View">
                                <i class="fas fa-binoculars"></i>
                            </a>
                            
                            @if(!$item->is_approve && !$item->is_reject)
                                <a href="#" class="btn btn-warning btn-sm mx-1" data-toggle="modal"
                                   data-target="#editPengajuanModal" data-id="{{ $item->id }}"
                                   data-prodi="{{ $item->prodi->id }}" data-judul="{{ $item->judul }}"
                                   data-edisi="{{ $item->edisi }}" data-isbn="{{ $item->isbn }}"
                                   data-penerbit="{{ $item->penerbit }}" data-author="{{ $item->author }}"
                                   data-tahun="{{ $item->tahun }}" data-eksemplar="{{ $item->eksemplar }}"
                                   data-toggle="tooltip" data-placement="top" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                    
                                <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                    
                    
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('user.edit')
        @include('user.create')
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
            modal.find('.modal-body #jumlah').val(allData.eksemplar);
        });

        $('#editPengajuanModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var prodi = button.data('prodi');
            var judul = button.data('judul');
            var edisi = button.data('edisi');
            var isbn = button.data('isbn');
            var penerbit = button.data('penerbit');
            var author = button.data('author');
            var tahun = button.data('tahun');
            var eksemplar = button.data('eksemplar');
            var modal = $(this);
            var form = modal.find('#editPengajuanForm');
            var actionUrl = '{{ route('home-update', ':id') }}'.replace(':id', id);
            form.attr('action', actionUrl);
            modal.find('.modal-body #prodi').val(prodi);
            modal.find('.modal-body #judul').val(judul);
            modal.find('.modal-body #edisi').val(edisi);
            modal.find('.modal-body #isbn').val(isbn);
            modal.find('.modal-body #penerbit').val(penerbit);
            modal.find('.modal-body #author').val(author);
            modal.find('.modal-body #tahun').val(tahun);
            modal.find('.modal-body #eksemplar').val(eksemplar);
        });

    </script>
@endpush
