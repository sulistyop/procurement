@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <!-- Stylish Title with Shadow -->
                <h1 class="display-4 text-primary font-weight-bold text-shadow">Parent Pengajuan</h1>
                <p class="lead text-muted">Kelola data pengajuan dengan mudah dan terstruktur.</p>
            </div>
        </div>

        <!-- Button for Adding Parent Pengajuan -->
        <div class="row mb-4">
            <div class="col-md-12 text-right">
                <!-- Button placed apart from the title for better focus -->
                <a href="{{ route('admin.parent-pengajuan.create') }}" class="btn btn-lg btn-success">
                    <i class="fa fa-plus-circle"></i> Tambah Rumah Pengajuan
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form method="get" action="{{ route('admin.parent-pengajuan.index') }}">
                    <div class="input-group">
                        <select id="prodi-filter" name="prodi" class="form-control custom-select" onchange="this.form.submit()">
                            <option value="">Semua Prodi/Unit</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" 
                                    {{ request('prodi', 1) == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Prodi/Unit</th>
                        <th>Tanggal Pembuatan</th>  <!-- Kolom Tanggal Pembuatan -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parentPengajuans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->prodi->nama ?? 'Tidak ada Prodi' }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>  <!-- Menampilkan Tanggal Pembuatan -->
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.parent-pengajuan.view', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Lihat Buku
                                </a>
                                <a href="{{ route('admin.parent-pengajuan.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil-alt"></i> Edit
                                </a>
                                @if($item->canDelete)
                                    <form action="{{ route('admin.parent-pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-custom mx-1 btn-sm" onclick="return confirm('Yakin ingin menghapus?')" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Pesan atau tombol lain jika tidak bisa dihapus -->
                                    <button class="btn btn-danger btn-sm mx-1" disabled>
                                        <i class="fas fa-trash"></i> approval
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Stylish Title with Shadow */
    .display-4 {
        font-size: 3.5rem;
        font-weight: 700;
        color: #3f5d7e;
        text-shadow: 2px 2px 4px rgb(52, 177, 226); /* Text Shadow Effect */
    }

    .lead {
        font-size: 1.25rem;
        color: #6c757d;
    }

    /* Table Styles */
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .table th {
        background-color: #343a40;
        color: white;
        font-weight: bold;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Styling for the filter form */
    .input-group {
        width: 100%;
    }

    .form-control {
        border-radius: 10px;
        box-shadow: none;
    }

    .btn-group .btn {
        font-size: 14px;
    }

    .btn-lg {
        padding: 12px 30px;
    }

    .alert {
        font-size: 16px;
    }

    /* Button Group Styling */
    .btn-success {
        padding: 12px 25px;
        font-size: 16px;
        border-radius: 5px;
    }

    .btn-outline-secondary {
        border-radius: 20px;
    }

    .input-group-append .btn {
        border-radius: 0 20px 20px 0;
    }
</style>
@endpush
