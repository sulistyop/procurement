@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <!-- Stylish Title with Shadow -->
                <h1 class="display-4 text-primary font-weight-bold text-shadow">Pengajuan</h1>
                <hr class="my-4 border-top border-primary">
                <p class="lead text-muted">Kelola data pengajuan dengan mudah dan terstruktur.</p>
            </div>
        </div>

        <!-- Button for Adding Parent Pengajuan -->
        <div class="row mb-4">
            <div class="col-md-12 text-right">
                <!-- Button placed apart from the title for better focus -->
                <a href="{{ route('admin.parent-pengajuan.create') }}" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> Tambah Pengajuan
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form method="get" action="{{ route('admin.parent-pengajuan.index') }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="prodi-filter" class="form-label">Prodi/Unit:</label>
                            <div class="input-group">
                                <select id="prodi-filter" name="prodi" class="form-control custom-select select2" onchange="this.form.submit()">
                                    <option value="">Semua Prodi/Unit</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}" 
                                            {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun-filter" class="form-label">Tahun:</label>
                            <div class="input-group">
                                <select id="tahun-filter" name="tahun" class="form-control custom-select select2" onchange="this.form.submit()">
                                    <option value="">Semua Tahun</option>
                                    @foreach(range(date('Y'), 2024) as $year)
                                        <option value="{{ $year }}" 
                                            {{ request('tahun') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
            <table id="customerspar" class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Prodi/Unit</th>
                        <th>Tanggal Pembuatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parentPengajuans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->prodi->nama ?? 'Tidak ada Prodi' }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.parent-pengajuan.view', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.parent-pengajuan.edit', $item->id) }}" class="btn btn-warning btn-sm ml-1">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                @if($item->canDelete)
                                    <form action="{{ route('admin.parent-pengajuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-danger btn-sm mx-1" disabled>
                                        <i class="fas fa-trash"></i>
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
@push('script-page')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#customerspar').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
@endpush