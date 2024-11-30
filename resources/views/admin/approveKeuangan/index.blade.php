@extends('admin.layouts.app')

@section('title')
    Keuangan
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
           LAPORAN KEUANGAN
        </h1>
        <hr class="my-4 border-top border-primary">
        
        <div class="mb-2">
            <a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#tambahApproveKeuanganModal">
                Tambah Approvement
                <i class="fas fa-plus"></i>
            </a>
        </div>
        <table class="table mt-4" id="customers">
            <thead>
            <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                <th colspan="2" class="text-center">Surat</th>
                <th colspan="2" class="text-center">Bukti Transaksi</th>
                <th colspan="2" class="text-center">Parent & Prodi</th>
                <th colspan="2" class="text-center">Aksi</th>
            </tr>
            <tr>
                <th>Nomor Surat</th>
                <th>File</th>
                <th>Nomor Bukti Transaksi</th>
                <th>File</th>
                <th>Parent</th>
                <th>Prodi</th>
                <th>Edit</th>
                <th>Hapus</th>
            </tr>
            </thead>
            <tbody>
            @foreach($approveKeuangan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nomorSurat }}</td>
                    <td>
                        @if($item->surat)
                        <a href="{{ asset('storage/' . $item->surat) }}" target="_blank">Lihat Surat</a>
                        @else
                            - 
                        @endif
                    </td>
                    <td>{{ $item->nomorBukti }}</td>
                    <td>
                        @if($item->buktiTransaksi)
                        <a href="{{ asset('storage/' . $item->buktiTransaksi) }}" target="_blank">Lihat Bukti Transaksi</a>
                        @else
                            - 
                        @endif
                    </td>
                    <td>
                        @foreach($item->parents as $parent)
                            <p>{{ $parent->nama }}</p>
                        @endforeach
                    </td>
                    <td>
                        @foreach($item->parents as $parent)
                            <p>{{ $parent->prodi->nama }}</p>
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        <a href="#" class="btn btn-warning btn-sm editApproveKeuangan" data-toggle="modal"
                           data-target="#editApproveKeuanganModal"
                           data-id="{{ $item->id }}"
                           data-nomorsurat="{{ $item->nomorSurat }}"
                           data-nomorbukti="{{ $item->nomorBukti }}"
                           data-surat="{{ $item->surat }}"
                           data-buktitransaksi="{{ $item->buktiTransaksi }}"
                           data-parentpengajuanids="{{ $item->parents->pluck('id')->join(',') }}" 
                           data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>                    
                    <td style="text-align: center;">
                        <form action="{{ route('approve-keuangan.destroy', $item->id) }}" method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip"
                                    data-placement="top" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @include('admin.component.edit-approve-keuangan-modal')
    @include('admin.component.tambah-approve-keuangan-modal')
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

            // Inisialisasi select2 pada modal edit
            $('#editApproveKeuanganModal').on('shown.bs.modal', function () {
                $('#parent_pengajuan').select2({
                    placeholder: "Pilih Parent Pengajuan",
                    allowClear: true,
                    dropdownParent: $('#editApproveKeuanganModal')
                });
            });

            $(document).on('click', '.editApproveKeuangan', function () {
                const id = $(this).data('id');
                const nomorSurat = $(this).data('nomorsurat');
                const nomorBukti = $(this).data('nomorbukti');
                const surat = $(this).data('surat');
                const buktiTransaksi = $(this).data('buktitransaksi');
                const parentPengajuanIds = $(this).data('parentpengajuanids');

                // Set the action URL untuk form
                $('#editApproveKeuanganForm').attr('action', '{{ route('approve-keuangan.update', ':id') }}'.replace(':id', id));

                // Set nilai form di modal
                $('#nomorSurat').val(nomorSurat);
                $('#nomorBukti').val(nomorBukti);
                $('#currentSurat').attr('href', `/storage/${surat}`);
                $('#currentBukti').attr('href', `/storage/${buktiTransaksi}`);

                // Set nilai untuk parent_pengajuan_id dalam select2
                $('#parent_pengajuan').val(parentPengajuanIds.split(',')).trigger('change');
            });
        });

        @if ($errors->any())
        $(document).ready(function () {
            $('#tambahApproveKeuanganModal').modal('show');
        });
        @endif
    </script>
@endpush
