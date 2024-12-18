<!-- resources/views/components/tambah-pengajuan-modal.blade.php -->
<div class="modal fade" id="tambahPengajuanModal" tabindex="-1" role="dialog" aria-labelledby="tambahPengajuanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPengajuanModalLabel">Tambah Pengajuan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    if(!isset($routeView)){
                        $route = '';
                    }
                @endphp
                <form action="{{ route('pengajuan.store') }}" method="POST">
                    @csrf
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
                        <label for="parent_pengajuan_id">Parent Pengajuan</label>
                        <select name="parent_pengajuan_id" id="parent_pengajuan_id" class="form-control" disabled>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" 
                                    @if($parent->id == $idParent) selected @endif>
                                    {{ $parent->nama }}
                                </option>
                            @endforeach
                        </select>      
                        <input type="hidden" name="parent_pengajuan_id" value="{{ $idParent }}">                                         
                        @error('parent_pengajuan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                        @error('judul')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edisi">Edisi</label>
                        <input type="text" class="form-control" id="edisi" name="edisi" value="{{ old('edisi') }}">
                        @error('edisi')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn') }}">
                        @error('isbn')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit') }}">
                        @error('penerbit')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="author">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author" value="{{ old('author') }}" required>
                        @error('author')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" value="{{ old('tahun') }}">
                        @error('tahun')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="eksemplar">Jumlah Eksemplar</label>
                        <input type="number" class="form-control" id="eksemplar" name="eksemplar" value="{{ old('eksemplar') }}" required>
                        @error('eksemplar')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
