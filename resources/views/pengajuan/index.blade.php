@extends('layouts.app')

@section('title')
Pengajuan
@endsection

@push('style-page')
    <style>
        .custom-select{
            width: 70px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h1>Daftar Pengajuan</h1>
        <div class="mb-2">
            <a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#tambahPengajuanModal">
                Tambah Pengajuan
                <i class="fas fa-plus"></i>
            </a>
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
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Jumlah</th>
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
                        {{--data ini pernah diajukan di tahun --}}
                        @if($item->is_diajukan)
                            {{ $item->isbn }} <span class="badge badge-info">Pernah diajukan tahun  {{ \Illuminate\Support\Carbon::parse($item->date_pernah_diajukan)->format('d-m-Y')  }}</span>
                        @else
                            {{ $item->isbn }}
                        @endif
                    </td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penerbit }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->eksemplar }}</td>
                    <td>
                        @if($item->is_approve)
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    <td>
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="View">
                            <i class="fas fa-binoculars"></i>
                        </a>
                        @if(!$item->is_approve)
                            <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPengajuanModal" data-id="{{ $item->id }}" data-prodi="{{ $item->prodi->id }}" data-judul="{{ $item->judul }}" data-edisi="{{ $item->edisi }}" data-isbn="{{ $item->isbn }}" data-penerbit="{{ $item->penerbit }}" data-author="{{ $item->author }}" data-tahun="{{ $item->tahun }}" data-eksemplar="{{ $item->eksemplar }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip" data-placement="top" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal" data-all="{{ $item }}" data-id="{{ $item->id }}" data-jumlah="{{ $item->eksemplar }}" data-toggle="tooltip" data-placement="top" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                
            @endforeach
            </tbody>
        </table>

        @include('component.edit-modal')
        <!-- Include the Modal Component -->
        @include('component.tambah-pengajuan-modal')
        <!-- Single Approve Modal -->
        @include('component.approve-modal')
        <!-- Upload Modal -->
        @include('component.upload-modal')
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

        $('#approveModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var allData = button.data('all');
            var modal = $(this);
            var form = modal.find('#approveForm');
            var actionUrl = '{{ route('pengajuan.storeApproval', ':id') }}'.replace(':id', id);
            form.attr('action', actionUrl);

            modal.find('.modal-body #prodi').val(allData.prodi);
            modal.find('.modal-body #isbn').val(allData.isbn);
            modal.find('.modal-body #judul').val(allData.judul);
            modal.find('.modal-body #penerbit').val(allData.penerbit);
            modal.find('.modal-body #tahun').val(allData.tahun);
            modal.find('.modal-body #eksemplar').val(allData.eksemplar);
        });

        $('#approveForm').on('submit', function (event) {
            event.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Pengajuan approved successfully!',
                    }).then(() => {
                        $('#approveModal').modal('hide');
                        location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while approving the pengajuan.',
                    });
                }
            });
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
            var actionUrl = '{{ route('pengajuan.update', ':id') }}'.replace(':id', id);
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
    @if ($errors->any())
        <script type="text/javascript">
            $(document).ready(function() {
                $('#tambahPengajuanModal').modal('show');
            });
        </script>
    @endif
@endpush
