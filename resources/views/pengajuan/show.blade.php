@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Pengajuan</h1>
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
                <th>Tahun</th>
                <td>{{ $pengajuan->tahun }}</td>
            </tr>
            <tr>
                <th>Eksemplar</th>
                <td>{{ $pengajuan->eksemplar }}</td>
            </tr>
        </table>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('pengajuan.edit', $pengajuan->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('pengajuan.destroy', $pengajuan->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
        </form>
    </div>
@endsection
