@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Pengajuan</h1>
        <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="prodi">Prodi</label>
                @dd($pengajuan->prodi)
                <select class="form-control" id="prodi" name="prodi" value="{{ old('prodi', $pengajuan->prodi) }}" required>
                    <option value="Ilmu Hadis S1">Perpustakaan</option>
                    <option value="Ilmu Hadis S1">Lembaga Pengembangan dan Studi Islam</option>
                    <option value="bsa">Bahasa dan Sastra Arab S1</option>
                    <option value="Bahasa dan Sastra Arab S1">Bahasa dan Sastra Arab S1</option>
                    <option value="Ilmu Hadis S1">Ilmu Hadis S1</option>
                    <option value="Pendidikan Agama Islam S1">Pendidikan Agama Islam S1</option>
                    <option value="Perbankan Syariah S1">Perbankan Syariah S1</option>
                    <option value="Psikologi S1">Psikologi S1</option>
                    <option value="Ekonomi Pembangunan S1">Ekonomi Pembangunan S1</option>
                    <option value="Manajemen S1">Manajemen S1</option>
                    <option value="Akuntansi S1">Akuntansi S1</option>
                    <option value="Bisnis Jasa Makanan D4">Bisnis Jasa Makanan D4</option>
                    <option value="Sastra Inggris S1">Sastra Inggris S1</option>
                    <option value="Sastra Indonesia S1">Sastra Indonesia S1</option>
                    <option value="Ilmu Komunikasi S1">Ilmu Komunikasi S1</option>
                    <option value="Hukum S1">Hukum S1</option>
                    <option value="Bimbingan dan Konseling S1">Bimbingan dan Konseling S1</option>
                    <option value="Pendidikan Bahasa dan Sastra Indonesia S1">Pendidikan Bahasa dan Sastra Indonesia S1</option>
                    <option value="Pendidikan Bahasa Inggris S1">Pendidikan Bahasa Inggris S1</option>
                    <option value="Pendidikan Pancasila dan Kewarganegaraan S1">Pendidikan Pancasila dan Kewarganegaraan S1</option>
                    <option value="Pendidikan Matematika S1">Pendidikan Matematika S1</option>
                    <option value="Pendidikan Biologi S1">Pendidikan Biologi S1</option>
                    <option value="Pendidikan Fisika S1">Pendidikan Fisika S1</option>
                    <option value="Pendidikan Guru Sekolah Dasar S1">Pendidikan Guru Sekolah Dasar S1</option>
                    <option value="Pendidikan Guru PAUD S1">Pendidikan Guru PAUD S1</option>
                    <option value="Pendidikan Vokasional Teknologi Otomotif S1">Pendidikan Vokasional Teknologi Otomotif S1</option>
                    <option value="Pendidkan Vokasional Teknik Elektro S1">Pendidkan Vokasional Teknik Elektro S1</option>
                    <option value="Matematika S1">Matematika S1</option>
                    <option value="Sistem Informasi S1">Sistem Informasi S1</option>
                    <option value="Fisika S1">Fisika S1</option>
                    <option value="Biologi S1">Biologi S1</option>
                    <option value="Teknik Industri S1">Teknik Industri S1</option>
                    <option value="Informatika S1">Informatika S1</option>
                    <option value="Teknik Elektro S1">Teknik Elektro S1</option>
                    <option value="Teknik Kimia S1">Teknik Kimia S1</option>
                    <option value="Teknologi Pangan S1">Teknologi Pangan S1</option>
                    <option value="Farmasi S1">Farmasi S1</option>
                    <option value="Kesehatan Masyarakat S1">Kesehatan Masyarakat S1</option>
                    <option value="Gizi S1">Gizi S1</option>
                    <option value="Kedokteran S1">Kedokteran S1</option>
                    <option value="Pendidikan Profesi Guru">Pendidikan Profesi Guru</option>
                    <option value="Pendidikan Profesi Apoteker">Pendidikan Profesi Apoteker</option>
                    <option value="Pendidikan Profesi Dokter">Pendidikan Profesi Dokter</option>
                    <option value="Pendidikan Profesi Psikologi">Pendidikan Profesi Psikologi</option>
                    <option value="Pendidikan Agama Islam S2">Pendidikan Agama Islam S2</option>
                    <option value="Pendidikan Fisika S2">Pendidikan Fisika S2</option>
                    <option value="Pendidikan Guru Vokasi S2">Pendidikan Guru Vokasi S2</option>
                    <option value="Pendidikan Bahasa Inggris S2">Pendidikan Bahasa Inggris S2</option>
                    <option value="Pendidikan Matematika S2">Pendidikan Matematika S2</option>
                    <option value="Bimbingan dan Konseling S2">Bimbingan dan Konseling S2</option>
                    <option value="Manajemen Pendidikan S2">Manajemen Pendidikan S2</option>
                    <option value="Psikologi S2">Psikologi S2</option>
                    <option value="Farmasi S2">Farmasi S2</option>
                    <option value="Kesehatan Masyarakat S2">Kesehatan Masyarakat S2</option>
                    <option value="Informatika S2">Informatika S2</option>
                    <option value="Teknik Kimia S2">Teknik Kimia S2</option>
                    <option value="Teknik Elektro S2">Teknik Elektro S2</option>
                    <option value="Hukum S2">Hukum S2</option>
                    <option value="Ilmu Farmasi S3">Ilmu Farmasi S3</option>
                    <option value="Pendidikan S3">Pendidikan S3</option>
                    <option value="Informatika S3">Informatika S3</option>
                </select>
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
                <label for="tahun">Tahun Terbit</label>
                <input type="number" class="form-control" id="tahun" name="tahun" value="{{ old('tahun', $pengajuan->tahun) }}" required>
                @error('tahun')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="eksemplar">Jumlah Eksemplar</label>
                <input type="number" class="form-control" id="eksemplar" name="eksemplar" value="{{ old('eksemplar', $pengajuan->eksemplar) }}" required>
                @error('eksemplar')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
