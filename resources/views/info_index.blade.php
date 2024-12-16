@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{ $title }}</h1>

<a href="{{ route('info.create') }}" class="btn btn-primary mb-3">{{ __('info.add') }}</a>
<a href="{{ route('info.exportPDF', request()->all()) }}" class="btn btn-secondary mb-3">{{ __('info.export_pdf') }}</a>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive p-2">
                    <table id="info-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('info.no') }}</th>
                                <th>{{ __('info.title') }}</th>
                                <th>{{ __('info.category') }}</th>
                                <th>{{ __('info.date') }}</th>
                                <th>{{ __('info.description') }}</th>
                                <th>{{ __('info.photo') }}</th>
                                <th>{{ __('info.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($info as $item)
                                @php
                                    $formattedDate = formatDate($item->date); // Using the helper here
                                    $message = __('info.title') . ": {$item->title}\n" .
                                               __('info.date') . ": $formattedDate\n" .
                                               __('info.description') . ": " . ($item->description ?? __('info.no_description'));
                                    $whatsappLink = "https://wa.me/send?text=" . urlencode($message);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                                    <td>{!! $formattedDate !!}</td> <!-- Using formattedDate -->
                                    <td>{!! nl2br(e($item->description ?? '-')) !!}</td>

                                    <td>
                                        @if($item->photo)
                                            <img src="{{ asset('storage/infos/' . $item->photo) }}" alt="Photo" width="50" height="50">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('info.show', $item->id) }}" class="btn btn-secondary mb-1">{{ __('info.details') }}</a>
                                        <a href="{{ route('info.edit', $item->id) }}" class="btn btn-warning mb-1">{{ __('info.edit') }}</a>

                                        <!-- WhatsApp Share Button -->
                                        <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-success mb-1">
                                            <i class="bi bi-whatsapp pe-1"></i>{{ __('info.share_whatsapp') }}
                                        </a>

                                        <form action="{{ route('info.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mb-1">{{ __('info.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#info-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            columnDefs: [
                { orderable: false, targets: [5, 6] }
            ],
            "language": {
                "url": "{{ app()->getLocale() === 'ms' ? 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ms.json' : 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/en-GB.json' }}"
            }
        });
    });
</script>

<style>
    .dataTables_length {
        margin-top: 3px;
        margin-bottom: 3px;
    }

    .dataTables_filter {
        margin-top: 3px;
        margin-bottom: 3px;
    }
</style>
@endsection
