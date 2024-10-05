@extends('layouts.app')

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
    <h1>Laporan Keuangan</h1>
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
                <th colspan="2" class="text-center">Aksi</th>
            </tr>
            <tr>
                <th>Nomor Surat</th>
                <th>File</th>
                <th>Nomor Bukti Transaksi</th>
                <th>File</th>
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
                        <a href="{{ asset('storage/' . $item->buktiTransaksi) }}" target="_blank">Lihat Bukti</a>
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: center;">
                    <a href="#" class="btn btn-warning btn-sm editApproveKeuangan" data-toggle="modal" 
                       data-target="#editApproveKeuanganModal" 
                       data-id="{{ $item->id }}" 
                       data-nomorSurat="{{ $item->nomorSurat }}" 
                       data-surat="{{ $item->surat }}" 
                       data-nomorBukti="{{ $item->nomorBukti }}" 
                       data-buktiTransaksi="{{ $item->buktiTransaksi }}" 
                       data-toggle="tooltip" data-placement="top" title="Edit">
                       <i class="fas fa-edit"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <form action="{{ route('approve-keuangan.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip" data-placement="top" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@include('component.edit-approve-keuangan-modal')
@include('component.tambah-approve-keuangan-modal')
@endsection

@push('script-page')
@section('scripts')
<script>
$(document).on('click', '.editApproveKeuangan', function() {
    const id = $(this).data('id');
    const nomorSurat = $(this).data('nomorSurat');
    const nomorBukti = $(this).data('nomorBukti');
    const surat = $(this).data('surat');
    const buktiTransaksi = $(this).data('buktiTransaksi');

    // Set the action URL for the form
    $('#editApproveKeuanganForm').attr('action', `/approve-keuangan/${id}`);

    // Set the values in the modal
    $('#nomorSurat').val(nomorSurat);
    $('#nomorBukti').val(nomorBukti);
    
    // Set the current file links
    $('#currentSurat').attr('href', `/storage/${surat}`);
    $('#currentBukti').attr('href', `/storage/${buktiTransaksi}`);
});


</script>
@endsection

@if ($errors->any())
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tambahApproveKeuanganModal').modal('show');
        });
    </script>
@endif
@endpush
