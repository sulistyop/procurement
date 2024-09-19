@extends('layouts.app')

@section('title')
Pengajuan
@endsection

@push('style-page')
    <style>
        .custom-select{
            width: 70px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h1>Rekap Pengajuan</h1>
        <div class="mb-2">
            <a type="button" class="btn btn-outline-info" href="{{ route('rekap-pengajuan.index').'?export=true' }}">
                Export Excel
                <i class="fa fa-download"></i>
            </a>
        </div>
        <table class="table mt-4" id="customers">
            <thead>
            <tr>
                <th>No</th>
                <th>Prodi</th>
                <th>ISBN</th>
                <th>Judul</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Jumlah</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pengajuan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->prodi }}</td>
                    <td>
                        {{--data ini pernah diajukan di tahun --}}
                        @if($item->is_diajukan)
                            {{ $item->isbn }} <span class="badge badge-info">Pernah diajukan tahun  {{ \Illuminate\Support\Carbon::parse($item->date_pernah_diajukan)->format('d-m-Y')  }}</span>
                        @else
                            {{ $item->isbn }}
                        @endif
                    </td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penerbit }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->eksemplar }}</td>

                </tr>
                
            @endforeach
            </tbody>
        </table>
        <!-- Single Approve Modal -->
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Approve Pengajuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="approveForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="prodi">Prodi</label>
                                <input type="text" name="prodi" id="prodi" class="form-control" readonly>
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
                                <label for="eksemplar">Jumlah</label>
                                <input type="text" name="eksemplar" id="eksemplar" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#customers').DataTable({
                // Aktifkan fitur sorting, search, pagination, dan length
                "paging": true,         // Aktifkan pagination
                "lengthChange": true,   // Tampilkan dropdown untuk panjang data
                "searching": true,      // Aktifkan kolom pencarian
                "ordering": true,       // Aktifkan sorting kolom
                "info": true,           // Tampilkan informasi jumlah data
                "autoWidth": false,     // Nonaktifkan lebar otomatis
            });
        });
    </script>

    <script type="text/javascript">
        $('#approveModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var allData = button.data('all'); // Extract the entire item data
            var modal = $(this);
            var form = modal.find('#approveForm');
            var actionUrl = '{{ route('pengajuan.storeApproval', ':id') }}'.replace(':id', id); // Replace :id with the actual ID
            form.attr('action', actionUrl); // Set the form action dynamically

            // Populate the modal fields
            modal.find('.modal-body #prodi').val(allData.prodi);
            modal.find('.modal-body #isbn').val(allData.isbn);
            modal.find('.modal-body #judul').val(allData.judul);
            modal.find('.modal-body #penerbit').val(allData.penerbit);
            modal.find('.modal-body #tahun').val(allData.tahun);
            modal.find('.modal-body #eksemplar').val(allData.eksemplar);
        });

        $('#approveForm').on('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            var form = $(this);
            var actionUrl = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                success: function (response) {
                    // Handle success response
                    alert('Pengajuan approved successfully!');
                    $('#approveModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    alert('An error occurred while approving the pengajuan.');
                }
            });
        });
    </script>
@endpush
