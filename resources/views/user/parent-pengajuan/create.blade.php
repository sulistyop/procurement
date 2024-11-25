@extends('user.layouts.app-user')

@section('content')
    <div class="container">
        <h1>Tambah Parent Pengajuan</h1>

        <form action="{{ route('user.parent-pengajuan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>

            <!-- Menampilkan Prodi yang tidak bisa diubah -->
            <div class="form-group">
                <label for="prodi_id">Prodi</label>
                <!-- Menampilkan nama prodi secara statis -->
                <input type="text" class="form-control" value="{{ $prodi->first()->nama }}" readonly>
                
                <!-- Hidden input untuk menyimpan prodi_id -->
                <input type="hidden" name="prodi_id" value="{{ $prodi->first()->id }}">
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('welcome') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
