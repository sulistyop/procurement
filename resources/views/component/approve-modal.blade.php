<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST" action="/route-to-handle-approval">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_prodi">Prodi</label>
                        <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" name="isbn" id="isbn" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" name="tahun" id="tahun" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="eksemplar">Jumlah Diterima</label>
                        <input type="number" name="eksemplar" id="eksemplar" class="form-control" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" id="harga" value="0" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tahun">Alasan</label>
                        <input type="text" name="reason" id="reason" class="form-control">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <div>
                        <button type="submit" class="btn btn-danger" name="action" value="reject">Tolak</button>
                        <button type="submit" class="btn btn-primary" name="action" value="approve">Terima</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
