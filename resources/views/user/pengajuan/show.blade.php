@extends('user.layouts.app-user')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="mb-4 text-center">Detail Pengajuan</h1>
            <form action="{{ route('pengajuan.storeApproval', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-info text-white">Prodi</th>
                        <td>{{ $pengajuan->prodi->nama }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Judul</th>
                        <td>{{ $pengajuan->judul }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Edisi</th>
                        <td>{{ $pengajuan->edisi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">ISBN</th>
                        <td>{{ $pengajuan->isbn }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Penerbit</th>
                        <td>{{ $pengajuan->penerbit }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Penulis</th>
                        <td>{{ $pengajuan->author }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Tahun Terbit</th>
                        <td>{{ $pengajuan->tahun }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Usulan Eksemplar</th>
                        <td>{{ $pengajuan->eksemplar }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Usulan Diterima</th>
                        <td>{{ $pengajuan->diterima }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Tanggal Diajukan</th>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-info text-white">Status</th>
                        <td>
                            @if($pengajuan->is_approve)
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($pengajuan->is_reject)
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Belum disetujui</span>
                            @endif
                        </td>
                    </tr>
                    @if($pengajuan->reason)
                    <tr>
                        <th class="bg-info text-white">Alasan</th>
                        <td>{{ $pengajuan->reason }}</td>
                    </tr>
                    @endif
                </table>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('user.parent-pengajuan.view', $parentPengajuan->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>                
            </form>
        </div>
    </div>
    
    <script>
        // Menghapus titik sebelum submit
        document.getElementById('approveForm')?.addEventListener('submit', function(e) {
            let hargaInput = document.getElementById('harga');
            // Menghapus titik sebelum mengirim data
            if (hargaInput) {
                hargaInput.value = hargaInput.value.replace(/\./g, '');
            }
        });
    </script>
@endsection
