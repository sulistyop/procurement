@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Activity Logs</h1>
        <table class="table mt-4" id="customers">
            <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Model</th>
                <th>Timestamp</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($activityLogs as $log)
                @php
                    $actionClass = '';
                    if (stripos($log->action, 'Mengubah') !== false) {
                        $actionClass = 'text-warning';
                    } elseif (stripos($log->action, 'Membuat') !== false) {
                        $actionClass = 'text-success';
                    } elseif (stripos($log->action, 'Menyetujui') !== false) {
                        $actionClass = 'text-primary';
                    } elseif (stripos($log->action, 'Menolak') !== false) {
                        $actionClass = 'text-danger';
                    } elseif (stripos($log->action, 'Menghapus') !== false) {
                        $actionClass = 'text-danger';
                    } elseif (stripos($log->action, 'Import') !== false) {
                        $actionClass = 'text-info';
                    }
                @endphp
                <tr>
                    <td class="{{ $actionClass }}">{{ $log->id }}</td>
                    <td class="{{ $actionClass }}">{{ $log->user->name ?? 'N/A' }}</td>
                    <td class="{{ $actionClass }}">{{ $log->action }}</td>
                    <td class="{{ $actionClass }}">
                        @if($log->data)
                            <a href="{{ route('pengajuan.show', $log->data->id) }}" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="View">
                                {{ $log->data->judul }}
                            </a>
                        @else
                            {{ class_basename($log->model) }}
                        @endif
                    </td>
                    <td class="{{ $actionClass }}">{{ \Illuminate\Support\Carbon::parse($log->created_at)->translatedFormat('l, d F Y H:i:s') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('style-page')
    <style>
        .yellow { color: yellow; }
        .green { color: green; }
        .blue { color: blue; }
        .red { color: red; }
    </style>
@endpush

@push('script-page')
    <script>
        $(document).ready(function() {
            $('#customers').DataTable();
        });
    </script>
@endpush