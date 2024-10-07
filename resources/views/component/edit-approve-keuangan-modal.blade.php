<div class="modal fade" id="editApproveKeuanganModal" tabindex="-1" role="dialog" aria-labelledby="editApproveKeuanganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editApproveKeuanganModalLabel">Edit Approve Keuangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editApproveKeuanganForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nomorSurat">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomorSurat" name="nomorSurat" required>
                        @error('nomorSurat')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="surat">File Surat (Saat Ini)</label>
                        <div>
                            <a a href="{{ asset('storage/' . $item->surat) }}" target="_blank">Lihat Surat</a>
                        </div>
                        <label for="surat">Unggah File Surat</label>
                        <input type="file" class="form-control" id="surat" name="surat" accept="application/pdf">
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
                        <label for="buktiTransaksi">File Bukti Transaksi (Saat Ini)</label>
                        <div>
                            <a href="{{ asset('storage/' . $item->surat) }}" target="_blank">Lihat Bukti</a>
                        </div>
                        <label for="buktiTransaksi">Unggah File Bukti Transaksi</label>
                        <input type="file" class="form-control" id="buktiTransaksi" name="buktiTransaksi" accept="application/pdf">
                        @error('buktiTransaksi')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
