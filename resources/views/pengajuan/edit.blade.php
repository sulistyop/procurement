@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Pengajuan</h1>
        <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="prodi">Prodi</label>
                <input type="text" class="form-control" id="prodi" name="prodi" value="{{ old('prodi', $pengajuan->prodi) }}" required>
                @error('prodi')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $pengajuan->judul) }}" required>
                @error('judul')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="edisi">Edisi</label>
                <input type="text" class="form-control" id="edisi" name="edisi" value="{{ old('edisi', $pengajuan->edisi) }}">
                @error('edisi')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $pengajuan->isbn) }}" required>
                @error('isbn')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="penerbit">Penerbit</label>
                <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit', $pengajuan->penerbit) }}" required>
                @error('penerbit')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="author">Penulis</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $pengajuan->author) }}" required>
                @error('author')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="tahun">Tahun</label>
                <input type="number" class="form-control" id="tahun" name="tahun" value="{{ old('tahun', $pengajuan->tahun) }}" required>
                @error('tahun')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="eksemplar">Eksemplar</label>
                <input type="number" class="form-control" id="eksemplar" name="eksemplar" value="{{ old('eksemplar', $pengajuan->eksemplar) }}" required>
                @error('eksemplar')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
