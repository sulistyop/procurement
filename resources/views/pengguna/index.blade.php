@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Pengguna</h1>
        <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah Pengguna</a>
        <table class="table mt-3">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
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