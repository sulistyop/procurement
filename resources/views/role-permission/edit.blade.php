<!-- resources/views/role-permission/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Peran dan Hak Akses</h1>
        <form action="{{ route('roles-permissions.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="role">Peran</label>
                <select name="role_id" id="role" class="form-control" required>
                    <option value="">Pilih Peran</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="permissions">Permissions</label>
                <div id="permissions">
                    @foreach($permissions as $permission)
                        <div class="form-check">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-check-input" id="permission-{{ $permission->id }}">
                            <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>


@endsection

@push('script-page')
    <script>
        document.getElementById('role').addEventListener('change', function() {
            fetch(`/api/roles/${this.value}/permissions`)
                .then(response => response.json())
                .then(data => {
                    document.querySelectorAll('#permissions input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = data.permissions.includes(checkbox.value);
                    });
                });
        });
    </script>
@endpush