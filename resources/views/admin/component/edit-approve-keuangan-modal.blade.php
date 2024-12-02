<!-- Modal Edit Approve Keuangan -->
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
                    </div>
                    <div class="form-group">
                        <label for="surat">File Surat (Saat Ini)</label>
                        <div>
                            <a id="currentSurat" href="#" target="_blank">Lihat Surat</a>
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
                    </div>
                    <div class="form-group">
                        <label for="buktiTransaksi">File Bukti Transaksi (Saat Ini)</label>
                        <div>
                            <a id="currentBukti" href="#" target="_blank">Lihat Bukti</a>
                        </div>
                        <label for="buktiTransaksi">Unggah File Bukti Transaksi</label>
                        <input type="file" class="form-control" id="buktiTransaksi" name="buktiTransaksi" accept="application/pdf">
                        @error('buktiTransaksi')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> 
                    <div class="form-group">
                        <label for="parent_pengajuan">Pilih Parent Pengajuan:</label>
                        <select id="parent_pengajuan" name="parent_pengajuan_id[]" class="form-control select2" multiple="multiple">
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" id="parent_{{ $parent->id }}">
                                    {{ $parent->nama }} - {{ $parent->prodi->nama }}
                                </option>
                            @endforeach
                        </select>    
                        {{-- <div class="form-group">
                            <label for="parent_pengajuan">Pilih Parent Pengajuan:</label>
                            <select id="parent_pengajuan" name="parent_pengajuan[]" class="form-control" multiple="multiple">
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                                @endforeach
                            </select>                                              
                        </div>                     --}}
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>