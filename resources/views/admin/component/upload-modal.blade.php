<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Excel File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="{{ route('pengajuan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Format File Excel: judul - author - eksemplar</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>

                    <!-- Menambahkan input hidden untuk parent_pengajuan_id -->
                    <input type="hidden" name="parent_pengajuan_id" value="{{ request()->get('parent_pengajuan_id') }}">

                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
