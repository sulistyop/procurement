<div class="modal fade" id="tambahApproveKeuanganModal" tabindex="-1" role="dialog" aria-labelledby="tambahApproveKeuanganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahApproveKeuanganModalLabel">Tambah Approve Keuangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('approve-keuangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomorSurat">Nomor Surat</label>
                        <input type="text" id="nomorSurat" name="nomorSurat" class="form-control" placeholder="Masukkan Nomor Surat" required>
                    </div>
                    <div class="form-group">
                        <label for="surat">File Surat</label>
                        <input type="file" id="surat" name="surat" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label for="nomorBukti">Nomor Bukti Transaksi</label>
                        <input type="text" id="nomorBukti" name="nomorBukti" class="form-control" placeholder="Masukkan Nomor Bukti Transaksi" required>
                    </div>
                    <div class="form-group">
                        <label for="buktiTransaksi">File Bukti Transaksi</label>
                        <input type="file" id="buktiTransaksi" name="buktiTransaksi" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_pengajuan">Pilih Pengajuan</label>
                        <select id="parent_pengajuan" name="parent_pengajuan_id[]" class="form-control select2" multiple="multiple" style="width: 100%; height: auto; padding: 8px;">
                            @foreach($parents as $parent)
                                @if(!in_array($parent->id, old('parent_pengajuan_id', [])) && !in_array($parent->id, $existingParentPengajuanIds)) 
                                    <option value="{{ $parent->id }}">
                                        {{ $parent->prodi->nama }} - {{ $parent->nama }}
                                    </option>
                                @endif
                            @endforeach
                        </select>   
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
