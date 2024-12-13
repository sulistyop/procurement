@extends('user.layouts.app-user')

@section('title')
    Pengajuan
@endsection

@section('content')
    <div class="container-fluid text-center my-4">
        <h1 class="rekap-title">Pengajuan Buku</h1>
    </div>
    <div class="container-fluid">
        @if(session('import_errors'))
            <div class="alert alert-danger">
                <ul>
                    @foreach(session('import_errors') as $failure)
                        <li>{{ $failure->errors()[0] }} di baris {{ $failure->row() }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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


        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#tambahPengajuanModal">
                    <i class="fas fa-plus"></i> Tambah Pengajuan
                </button>
                <a type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#uploadModal">
                    <i class="fa fa-upload"></i> Upload Excel
                </a>
            </div>
            <div class="ml-auto">
                <a type="button" class="btn btn-outline-info" href="{{ route('home').'?export=true' }}">
                    <i class="fa fa-download"></i> Download Excel
                </a>
            </div>
        </div>


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mt-3" id="customers">
                        <thead class="thead-dark">
                        <tr>
                            <th>No</th>
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
                                <td>
                                    {{--data ini pernah diajukan di tahun --}}
                                    @if($item->is_diajukan)
                                        {{ $item->isbn }} <span
                                                class="badge badge-info">Pernah diajukan tahun  {{ \Illuminate\Support\Carbon::parse($item->date_pernah_diajukan)->format('d-m-Y')  }}</span>
                                    @else
                                        {{ $item->isbn }}
                                    @endif
                                </td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->author }}</td>
                                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @if($item->is_approve)
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($item->is_reject)
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Proses</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('home-show', $item->id) }}" class="btn btn-info btn-custom mx-1 btn-sm" data-toggle="tooltip" data-placement="top" title="View">
                                            <i class="fas fa-binoculars"></i>
                                        </a>
                                        
                                        @if(!$item->is_approve && !$item->is_reject)
                                            <a href="#" class="btn btn-warning btn-custom mx-1 btn-sm" 
                                                data-toggle="modal" data-target="#editPengajuanModal"
                                                data-id="{{ $item->id }}" 
                                                data-prodi="{{ $item->prodi->id ?? '' }}"
                                                data-judul="{{ $item->judul ?? '' }}" 
                                                data-edisi="{{ $item->edisi ?? '' }}"
                                                data-isbn="{{ $item->isbn ?? '' }}" 
                                                data-penerbit="{{ $item->penerbit ?? '' }}"
                                                data-author="{{ $item->author ?? '' }}" 
                                                data-tahun="{{ $item->tahun ?? '' }}"
                                                data-eksemplar="{{ $item->eksemplar ?? '' }}" 
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        
                                            <form action="{{ route('home-destroy', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-custom mx-1 btn-sm" onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip" data-placement="top" title="Delete">
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
                </div>
            </div>
        </div>

        @include('user.pengajuan.edit')
        @include('user.pengajuan.create')
        @include('admin.component.upload-modal')
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#customers').DataTable({
                // "paging": true,
                // "lengthChange": true,
                "searching": true,
                // "ordering": true,
                // "info": true,
                // "autoWidth": false,
            });
        });

        $('#editPengajuanModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Tombol yang membuka modal
            var id = button.data('id');
            var prodi = button.data('prodi');
            var judul = button.data('judul');
            var edisi = button.data('edisi');
            var isbn = button.data('isbn');
            var penerbit = button.data('penerbit');
            var author = button.data('author');
            var tahun = button.data('tahun');
            var eksemplar = button.data('eksemplar');
            console.log('Data diterima:', { id, prodi, judul, edisi, isbn, penerbit, author, tahun, eksemplar });

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

        $(document).ready(function() {
            $('#customers tbody').on('click', 'tr', function() {
                $('#customers tr').removeClass('highlighted-row');
                $(this).addClass('highlighted-row');
            });
        });
    </script>
@endpush
