<!-- resources/views/components/edit-pengajuan-modal.blade.php -->
<div class="modal fade" id="editPengajuanModal" tabindex="-1" role="dialog" aria-labelledby="editPengajuanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPengajuanModalLabel">Edit Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPengajuanForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="prodi_id">Prodi</label>
                        <select class="form-control select2" id="prodi" name="prodi_id" value="{{ old('prodi_id') }}" required>
                            @foreach($prodi as $item)
                                <option value="{{ $item->id }}" @selected(old('prodi', ) == $item->id)>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                        @error('judul')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edisi">Edisi</label>
                        <input type="text" class="form-control" id="edisi" name="edisi">
                        @error('edisi')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                        @error('isbn')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                        @error('penerbit')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="author">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                        @error('author')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" required>
                        @error('tahun')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="eksemplar">Jumlah Eksemplar</label>
                        <input type="number" class="form-control" id="eksemplar" name="eksemplar" required>
                        @error('eksemplar')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>