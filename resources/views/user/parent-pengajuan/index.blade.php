@extends('user.layouts.app-user')

@section('content')
<div class="container mt-5">
    <!-- Title Section -->
    <div class="text-center mb-5">
        <h1 class="rekap-title text-primary">Pengelolaan Pengajuan</h1>
        <p class="text-muted">Kelola semua rumah pengajuan dengan lebih mudah dan efisien</p>
        <hr class="w-50 mx-auto border-primary">
    </div>

    <!-- Add New Button -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('user.parent-pengajuan.create') }}" class="btn btn-outline-primary shadow-sm rounded-pill">
            <i class="fas fa-plus me-2"></i> Tambah Rumah Pengajuan
        </a>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cards Section -->
    <div class="row g-4">
        @forelse($parentPengajuans as $item)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-lg border-0 h-100 rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <i class="fas fa-home fa-4x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark">{{ $item->nama }}</h5>
                        <p class="card-text text-muted mb-3">{{ $item->created_at->format('d M Y') }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <!-- View Button -->
                            <a href="{{ route('user.parent-pengajuan.view', $item->id) }}" 
                               class="btn btn-outline-info btn-sm rounded-pill" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <!-- Edit Button -->
                            <a href="{{ route('user.parent-pengajuan.edit', $item->id) }}" 
                               class="btn btn-outline-warning btn-sm rounded-pill" 
                               title="Edit Data">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <!-- Delete Button -->
                            @if($item->canDelete)
                                <form action="{{ route('user.parent-pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-outline-danger btn-sm rounded-pill" 
                                            onclick="return confirm('Yakin ingin menghapus?')" 
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-danger btn-sm rounded-pill" disabled>
                                    <i class="fas fa-ban"></i> Approval
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted fs-5">Belum ada data rumah pengajuan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">
<style>
    .rekap-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #3498DB;
    }

    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .btn {
        font-size: 0.875rem;
    }

    .btn-outline-primary {
        background-color: #fff;
        color: #3498DB;
        border-color: #3498DB;
        box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
    }

    .btn-outline-primary:hover {
        background-color: #3498DB;
        color: #fff;
    }

    .btn-outline-info:hover, 
    .btn-outline-warning:hover, 
    .btn-outline-danger:hover {
        color: white;
    }

    .btn-outline-info:hover {
        background-color: #3498DB;
        border-color: #3498DB;
    }

    .btn-outline-warning:hover {
        background-color: #F39C12;
        border-color: #F39C12;
    }

    .btn-outline-danger:hover {
        background-color: #E74C3C;
        border-color: #E74C3C;
    }
</style>
@endsection

@section('scripts')
<script>
    // Tooltip Initialization
    document.addEventListener('DOMContentLoaded', () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map((el) => new bootstrap.Tooltip(el));
    });
</script>
@endsection
@push('script-page')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#customers').DataTable({
                // "paging": true,
                // "lengthChange": true,
                "searching": true,
                // "ordering": true,
                // "info": true,
                // "autoWidth": false,
            });
        });

        $(document).ready(function() {
            $('#customers tbody').on('click', 'tr', function() {
                $('#customers tr').removeClass('highlighted-row');
                $(this).addClass('highlighted-row');
            });
        });
    </script>
@endpush
