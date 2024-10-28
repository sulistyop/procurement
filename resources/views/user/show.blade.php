@extends('user.layouts.app-user')

@section('content')
    <div>
        <h1>Detail Pengajuan</h1>
        <form action="{{ route('pengajuan.storeApproval', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table class="table">
                <tr>
                    <th>Prodi</th>
                    <td>{{ $pengajuan->prodi->nama }}</td>
                </tr>
                <tr>
                    <th>Judul</th>
                    <td>{{ $pengajuan->judul }}</td>
                </tr>
                <tr>
                    <th>Edisi</th>
                    <td>{{ $pengajuan->edisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td>{{ $pengajuan->isbn }}</td>
                </tr>
                <tr>
                    <th>Penerbit</th>
                    <td>{{ $pengajuan->penerbit }}</td>
                </tr>
                <tr>
                    <th>Penulis</th>
                    <td>{{ $pengajuan->author }}</td>
                </tr>
                <tr>
                    <th>Tahun Terbit</th>
                    <td>{{ $pengajuan->tahun }}</td>
                </tr>
                <tr>
                    <th>Usulan Eksemplar</th>
                    <td>{{ $pengajuan->eksemplar }}</td>
                </tr>
                <tr>
                    <th>Usulan Diterima</th>
                    <td>{{ $pengajuan->diterima }}</td>
                </tr>
                <tr>
                    <th>Tanggal Diajukan</th>
                    <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                </tr>
                @role('admin')
                <tr>
                    <th>Harga</th>
                    <td>Rp. {{ number_format($pengajuan->harga, 2, ',', '.') }}</td>
                </tr>
                @endrole
                <tr>
                    <th>Status</th>
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
                    <th>Alasan</th>
                    <td>{{ $pengajuan->reason }}</td>
                </tr>
                @endif
            </table>
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
        </form>


    </div>
    <script>
        document.getElementById('harga').addEventListener('input', function (e) {
            let value = this.value.replace(/\D/g, ''); // Menghapus semua karakter non-digit
            if (value) {
                // Menambahkan titik sebagai pemisah ribuan
                this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        });

        // Menghapus titik sebelum submit
        document.getElementById('approveForm').addEventListener('submit', function(e) {
            let hargaInput = document.getElementById('harga');
            // Menghapus titik sebelum mengirim data
            hargaInput.value = hargaInput.value.replace(/\./g, '');
        });
    </script>
@endsection


