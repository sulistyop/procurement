@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center text-primary font-weight-bold">
            Daftar Pengguna
        </h1>
        <hr class="my-4 border-top border-primary">
        
        <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>
        <table class="table mt-3" id="user-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Prodi</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->prodi ? $user->prodi->nama : '' }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                    <td>
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('script-page')
    {{--jadikan datatable--}}
    <script>
        $(document).ready(function() {
            $('#user-table').DataTable();
        } );
    </script>
@endpush