@extends('user.layouts.app-user')

@section('content')
<div class="container mt-5">
    <!-- Title Section -->
    <div class="text-center mb-4">
        <h1 class="text-primary fw-bold">Edit Parent Pengajuan</h1>
        <p class="text-muted">Perbarui nama rumah pengajuan dengan mudah</p>
        <hr class="w-50 mx-auto border-primary">
    </div>

    <!-- Edit Form Section -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <!-- Form Title -->
                    <h5 class="mb-4 text-center text-dark fw-bold">Form Edit Data</h5>

                    <!-- Form -->
                    <form action="{{ route('user.parent-pengajuan.update', $parentPengajuan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Field: Nama -->
                        <div class="form-group mb-4">
                            <label for="nama" class="form-label fw-bold">Nama Rumah Pengajuan</label>
                            <input type="text" name="nama" id="nama" 
                                   class="form-control shadow-sm @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama', $parentPengajuan->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('user.parent-pengajuan.index') }}" 
                               class="btn btn-secondary rounded-pill px-4 shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">
<style>
    h1 {
        font-size: 2rem;
    }

    .card {
        background-color: #ffffff;
        border: none;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
    }

    .form-control {
        border-radius: 8px;
        border-color: #dcdcdc;
    }

    .form-control:focus {
        border-color: #3498DB;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .btn {
        font-size: 0.9rem;
        font-weight: bold;
    }

    .btn-success, .btn-secondary {
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #28a745;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }

    .btn-secondary:hover {
        background-color: #6c757d;
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Edit Form Loaded Successfully!');
    });
</script>
@endsection
