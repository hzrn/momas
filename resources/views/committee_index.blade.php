@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title }}</h1>
<a href="{{ route('committee.create') }}" class="btn btn-primary mb-3">{{ __('committee.add') }}</a>
<a href="{{ route('committee.exportPDF', request()->all()) }}" class="btn btn-secondary mb-3">{{ __('committee.export_pdf') }}</a>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive p-2">
                    <table id="committee-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('committee.no') }}</th>
                                <th>{{ __('committee.name') }}</th>
                                <th>{{ __('committee.phone') }}</th>
                                <th>{{ __('committee.position') }}</th>
                                <th>{{ __('committee.address') }}</th>
                                <th>{{ __('committee.photo') }}</th>
                                <th>{{ __('committee.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($committee as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone_num }}</td>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>
                                        @if($item->photo)
                                            <img src="{{ $item->photo }}" alt="{{ __('committee.photo') }}" width="50" height="50">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('committee.show', $item->id) }}" class="btn btn-secondary mb-1">{{ __('committee.details') }}</a>
                                        <a href="{{ route('committee.edit', $item->id) }}" class="btn btn-warning mb-1">{{ __('committee.edit') }}</a>
                                        <form action="{{ route('committee.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mb-1">{{ __('committee.delete') }}</button>
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
        $('#committee-table').DataTable({
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

@endsection
