@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{$title}}</h1>
    <a href="{{ route('profile.create') }}" class="btn btn-primary mb-3">{{__('profile.add')}}</a>
    <a href="{{ route('profile.exportPDF', request()->all()) }}" class="btn btn-secondary mb-3 d-none">{{__('profile.export_pdf')}}</a>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table id="profile-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__('profile.no')}}</th>
                        <th class="d-none">Mosque ID</th>
                        <th>{{__('profile.title')}}</th>
                        <th>{{__('profile.category')}}</th>
                        <th>{{__('profile.content')}}</th>
                        <th>{{__('profile.action')}}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($profile as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-none">{{ $item->mosque_id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ __('profile.' . strtolower($item->category)) }}</td>
                        <td>{!! nl2br(e($item->content)) !!}</td>
                        <td>
                            <a href="{{ route('profile.show', $item->id) }}" class="btn btn-secondary mb-1">{{__('profile.details')}}</a>
                            <a href="{{ route('profile.edit', $item->id) }}" class="btn btn-warning mb-1">{{__('profile.edit')}}</a>
                            <form action="{{ route('profile.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mb-1">{{__('profile.delete')}}</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function () {
            const table = $('#profile-table').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "responsive": true,
                columnDefs: [
                    { orderable: false, targets: 5 }, // Disable ordering for Action column
                    {
                        targets: 4, // Target Content column
                        render: function (data, type, row, meta) {
                            return `<div style="min-width: 200px;">${data}</div>`;
                        }
                    }
                ],
                "language": {
                    "url": "{{ app()->getLocale() === 'ms' ? 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ms.json' : 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/en-GB.json' }}"
                }
            });

            // Recalculate column widths when the window is resized
            $(window).on('resize', function () {
                table.columns.adjust();
            });
        });
    </script>



@endsection
