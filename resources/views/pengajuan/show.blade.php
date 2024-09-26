@extends('layouts.app')

@section('content')
    <div>
        <h1>Detail Pengajuan</h1>
        <form action="{{ route('pengajuan.storeApproval', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="table">
                <tr>
                    <th>Prodi</th>
                    <td>{{ $pengajuan->prodi }}</td>
                </tr>
                <tr>
                    <th>Judul</th>
                    <td>{{ $pengajuan->judul }}</td>
                </tr>
                <tr>
                    <th>Edisi</th>
                    <td>{{ $pengajuan->edisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td>{{ $pengajuan->isbn }}</td>
                </tr>
                <tr>
                    <th>Penerbit</th>
                    <td>{{ $pengajuan->penerbit }}</td>
                </tr>
                <tr>
                    <th>Penulis</th>
                    <td>{{ $pengajuan->author }}</td>
                </tr>
                <tr>
                    <th>Tahun Terbit</th>
                    <td>{{ $pengajuan->tahun }}</td>
                </tr>
                <tr>
                    <th>Jumlah Eksemplar</th>
                    <td>{{ $pengajuan->eksemplar }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($pengajuan->is_approve)
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-warning">Belum disetujui</span>
                        @endif
                    </td>
                </tr>
            </table>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>


    </div>
@endsection
