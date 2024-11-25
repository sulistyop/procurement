@extends('user.layouts.app-user')

@section('content')
    <div class="container">
        <h1>Parent Pengajuan</h1>
        <a href="{{ route('user.parent-pengajuan.create') }}" class="btn btn-primary mb-3">Tambah Parent Pengajuan</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Prodi/Unit</th>  <!-- Kolom Prodi/Unit -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parentPengajuans as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->prodi->nama ?? 'Tidak ada Prodi' }}</td>  <!-- Menampilkan Prodi/Unit -->
                    <td>
                        <a href="{{ route('user.parent-pengajuan.view', $item->id) }}" class="btn btn-info btn-sm">Lihat Buku</a>
                        <a href="{{ route('user.parent-pengajuan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('user.parent-pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
