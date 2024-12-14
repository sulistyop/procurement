@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="display-4 text-center text-primary mb-4">Edit Parent Pengajuan</h1>

        <form action="{{ route('admin.parent-pengajuan.update', $parentPengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nama" class="font-weight-bold">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control custom-input" value="{{ $parentPengajuan->nama }}" required>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-success custom-btn">Update</button>
                <a href="{{ route('admin.parent-pengajuan.index') }}" class="btn btn-secondary custom-btn ml-3">Kembali</a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    /* Container Padding */
    .container {
        padding: 30px;
        max-width: 800px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Header Styling */
    .display-4 {
        font-size: 3rem;
        font-weight: 700;
        color: #3f5d7e;
    }

    /* Input Field Customization */
    .custom-input {
        border-radius: 8px;
        box-shadow: none;
        padding: 10px 15px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .custom-input:focus {
        border-color: #66afe9;
        box-shadow: 0 0 8px rgba(102, 175, 233, .6);
    }

    /* Button Customization */
    .custom-btn {
        padding: 10px 30px;
        font-size: 16px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .custom-btn:hover {
        transform: translateY(-2px);
    }

    .btn-secondary.custom-btn {
        background-color: #6c757d;
        border: none;
    }

    .btn-success.custom-btn {
        background-color: #28a745;
        border: none;
    }

    .btn-success.custom-btn:hover {
        background-color: #218838;
    }

    .btn-secondary.custom-btn:hover {
        background-color: #5a6268;
    }

    /* Form Group Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        font-weight: bold;
        font-size: 16px;
        color: #333;
    }
</style>
@endpush
