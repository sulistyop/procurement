@extends('layouts.app')

@section('title')
Pengajuan
@endsection

@section('content')
    <div class="container">
        <h1>Daftar Pengajuan</h1>
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">Tambah Pengajuan</a>
        <table class="table mt-3" id="table">
            <thead>
            <tr>
                <th>No</th>
                <th>Prodi</th>
                <th>ISBN</th>
                <th>Judul</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pengajuan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->prodi }}</td>
                    <td>
                        {{--data ini pernah diajukan di tahun --}}
                        @if($item->is_diajukan)
                            {{ $item->isbn }} <span class="badge badge-info">Pernah diajukan tahun  {{ \Illuminate\Support\Carbon::parse($item->date_pernah_diajukan)->format('Y')  }}</span>
                        @else
                            {{ $item->isbn }}
                        @endif
                    </td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penerbit }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('pengajuan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                // Aktifkan fitur sorting, search, pagination, dan length
                "paging": true,         // Aktifkan pagination
                "lengthChange": true,   // Tampilkan dropdown untuk panjang data
                "searching": true,      // Aktifkan kolom pencarian
                "ordering": true,       // Aktifkan sorting kolom
                "info": true,           // Tampilkan informasi jumlah data
                "autoWidth": false,     // Nonaktifkan lebar otomatis
            });
        });
    </script>
@endsection
