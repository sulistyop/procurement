@extends('user.layouts.app-user')

@section('content')
    <div class="container">
        <h1>Edit Parent Pengajuan</h1>

        <form action="{{ route('user.parent-pengajuan.update', $parentPengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ $parentPengajuan->nama }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('user.parent-pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
