@extends('layouts.app')

{{-- @section('title')
Pengajuan
@endsection --}}

@section('content')
    <div class="container">
        <table class="table mt-3" id="customers">
            <thead>
            <tr>
                <th>No</th>
                <th>Prodi</th>
                <th>ISBN</th>
                <th>Judul</th>
                <th>Penerbit</th>
                <th>Tahun Terbit</th>
                <th>Approve</th>
                <th>Admin</th>
                <th>Tanggal Approve</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pengajuan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->prodi }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->isbn }}</td>
                    <td>{{ $item->penerbit }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->is_approve }}</td>
                    <td>{{ $item->approved_by}}</td>
                    <td>{{ $item->approved_at }}</td>
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
