@extends('layouts.app')

@section('content')
    <div class="container">
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
                    
            {{-- <tr>
                <th>Jumlah Eksemplar</th>
                <td>{{ $pengajuan->eksemplar }}</td>
            </tr> --}}
                <tr>
                    <th><label for="eksemplar">Jumlah Eksemplar</label></th>
                    <td><input type="number" class="form-control" id="eksemplar" name="eksemplar" value="{{ old('eksemplar', $pengajuan->eksemplar) }}" required></td>
                    @error('eksemplar')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </tr>
            </table>
           <button type="submit" class="btn btn-primary">Submit</button>
           <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
        
        
    </div>
@endsection
