@extends('user.layouts.app-user')

@section('content')
    <div class="container mt-5">
        <!-- Title Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="container-fluid text-center my-4">
                <h1 class="rekap-title">Pengelolaan Pengajuan</h1>
            </div>
            <a href="{{ route('user.parent-pengajuan.create') }}" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-plus"></i> Tambah Rumah Pengajuan
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table Section -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Prodi/Unit</th>  <!-- Kolom Prodi/Unit -->
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parentPengajuans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->prodi->nama ?? 'Tidak ada Prodi' }}</td>  <!-- Menampilkan Prodi/Unit -->
                            <td>
                                <a href="{{ route('user.parent-pengajuan.view', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Buku
                                </a>
                                <a href="{{ route('user.parent-pengajuan.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('user.parent-pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal (optional if needed) -->
    <!-- You can add a modal for confirmation or other details -->
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">
    <style>
        /* Table custom styles */
        .table th, .table td {
            vertical-align: middle;
        }

        /* Button Customization */
        .btn {
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-success, .btn-info, .btn-warning, .btn-danger {
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover, .btn-info:hover, .btn-warning:hover, .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .alert {
            font-size: 16px;
            font-weight: 500;
        }

        .alert .close {
            font-size: 18px;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 Confirmation for Delete
        $('form').submit(function (e) {
            e.preventDefault();
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
                    this.submit();  // Submit the form if confirmed
                }
            });
        });
    </script>
@endsection
