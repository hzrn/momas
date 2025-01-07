@extends('layouts.app_adminkit')

@section('content')
@include('partials.delete_modal')

<h1 class="h3 mb-3">{{$title}}</h1>
<a href="{{route('item.create')}}" class="btn btn-primary mb-3">{{__('item.add')}}</a>
<a href="{{ route('item.exportPDF', request()->all()) }}" class="btn btn-secondary mb-3">{{__('item.export_pdf')}}</a>

<div class="row">
    <div class="col-12">
        <div class="card">
                <div class="card-body">
                    <div class="table-responsive p-2">
                    <table id="item-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{__('item.no')}}</th>
                                <th>{{__('item.name')}}</th>
                                <th>{{__('item.category')}}</th>
                                <th>{{__('item.description')}}</th>
                                <th>{{__('item.quantity')}}</th>
                                <th>{{__('item.price')}}</th>
                                <th>{{__('item.photo')}}</th>
                                <th>{{__('item.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->category->name ?? 'N/A'}}</td>
                                    <td>{{strip_tags($item->description ?? '-')}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{formatRM($item->price)}}</td>
                                    <td>
                                        @if($item->photo)
                                            <img src="{{ $item->photo }}" alt="{{ __('item.photo') }}" width="50" height="50">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('item.show', $item->id) }}" class="btn btn-secondary mb-1">{{__('item.details')}}</a>
                                        <a href="{{ route('item.edit', $item->id) }}" class="btn btn-warning mb-1">{{__('item.edit')}}</a>
                                        <form action="{{ route('item.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="{{ route('item.destroy', $item->id) }}">{{ __('committee.delete') }}</button>
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
        $('#item-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            columnDefs: [
                { orderable: false, targets: [6, 7] }
            ],
            "language": {
                "url": "{{ app()->getLocale() === 'ms' ? 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ms.json' : 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/en-GB.json' }}"
            }
        });
    });

                    // Handle the delete modal
                    $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var url = button.data('url'); // Extract info from data-* attributes
            var form = $('#deleteForm');
            form.attr('action', url);
        });
</script>

<style>
    .dataTables_length{
        margin-top: 3px;
        margin-bottom: 3px;
    }

    .dataTables_filter{
        margin-top: 3px;
        margin-bottom: 3px;
    }
</style>
@endsection
