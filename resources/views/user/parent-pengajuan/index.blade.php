@extends('user.layouts.app-user')

@section('content')
<div class="container mt-5">
    <!-- Title Section -->
    <div class="text-center my-4">
        <h1 class="rekap-title">Pengelolaan Pengajuan</h1>
    </div>

    <!-- Add New Button -->
    <div class="mb-4 text-end">
        <a href="{{ route('user.parent-pengajuan.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Tambah Rumah Pengajuan
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
    <div class="row">
        @forelse($parentPengajuans as $item)
            <div class="col-md-4 mb-4"> <!-- Kolom lebih responsif dengan margin bawah -->
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="card-icon mb-3">
                            <i class="fas fa-home fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title mb-2">{{ $item->nama }}</h5>
                        <p class="card-text text-muted">{{ $item->created_at->format('d M Y') }}</p> <!-- Ganti <td> menjadi <p> untuk teks yang lebih rapi -->
                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <!-- Lihat Button -->
                            <a href="{{ route('user.parent-pengajuan.view', $item->id) }}" 
                               class="btn btn-info btn-sm mx-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Lihat">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('user.parent-pengajuan.edit', $item->id) }}" 
                               class="btn btn-warning btn-sm mx-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            
                            <!-- Hapus Button -->
                            @if($item->canDelete)
                                <form action="{{ route('user.parent-pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm mx-1" 
                                            onclick="return confirm('Yakin ingin menghapus?')" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <!-- Pesan atau tombol lain jika tidak bisa dihapus -->
                                <button class="btn btn-danger btn-sm mx-1" disabled>
                                    <i class="fas fa-trash"></i> Approval
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada data rumah pengajuan.</p>
            </div>
        @endforelse
    </div>
    
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">
<style>
    .rekap-title {
        font-size: 2rem;
        font-weight: bold;
        color: #2C3E50;
    }

    .card-icon i {
        color: #3498DB;
    }

    .card {
        border-radius: 12px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .btn {
        border-radius: 25px;
        font-size: 14px;
    }

    .btn-sm {
        padding: 5px 15px;
    }

    .d-flex > .btn {
        margin-left: 5px;
        margin-right: 5px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tooltip initialization
    document.addEventListener('DOMContentLoaded', () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
    });

    // SweetAlert for Delete Confirmation
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection
