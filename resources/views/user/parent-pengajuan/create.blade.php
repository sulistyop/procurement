@extends('user.layouts.app-user')

@section('content')
    <div class="container">
        <h1>Tambah Parent Pengajuan</h1>

        <form action="{{ route('admin.parent-pengajuan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>

            <!-- Dropdown untuk memilih Prodi -->
            <div class="form-group">
                <label for="prodi_id">Prodi</label>
                <select name="prodi_id" id="prodi_id" class="form-control" required>
                    <option value="">Pilih Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.parent-pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
