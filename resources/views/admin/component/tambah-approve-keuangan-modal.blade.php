<!-- resources/views/components/tambah-approve-keuangan-modal.blade.php -->
<div class="modal fade" id="tambahApproveKeuanganModal" tabindex="-1" role="dialog" aria-labelledby="tambahApproveKeuanganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahApproveKeuanganModalLabel">Tambah Approvement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('approve-keuangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nomorSurat">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomorSurat" name="nomorSurat" required>
                        @error('nomorSurat')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="surat">File Surat (PDF)</label>
                        <input type="file" class="form-control" id="surat" name="surat" accept="application/pdf" required>
                        @error('surat')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="nomorBukti">Nomor Bukti Transaksi</label>
                        <input type="text" class="form-control" id="nomorBukti" name="nomorBukti" required>
                        @error('nomorBukti')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="buktiTransaksi">File Bukti Transaksi (PDF)</label>
                        <input type="file" class="form-control" id="buktiTransaksi" name="buktiTransaksi" accept="application/pdf" required>
                        @error('buktiTransaksi')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
