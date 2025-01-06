@extends('layouts.app_adminkit')

@section('content')
    <h1 class="h3 mb-3">{{$title}}</h1>
    <a href="{{ route('cashflow.create') }}" class="btn btn-primary mb-3">{{__('cashflow.add')}}</a>
    <a href="{{ route('cashflow.exportPDF', request()->all()) }}" class="btn btn-secondary mb-3">{{__('cashflow.export_pdf')}}</a>
    <a href="{{ route('cashflow.export') }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export to Excel
    </a>

    <form action="{{ route('cashflow.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-sm-3 ">
                <label for="start_date" class="form-label">{{__('cashflow.start_date')}}</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-sm-3 ">
                <label for="end_date" class="form-label">{{__('cashflow.end_date')}}</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-2  align-self-end">
                <button type="submit" class="btn btn-success">{{__('cashflow.filter')}}</button>
            </div>
        </div>

    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive p-2">
                <table id="cashflow-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('cashflow.no') }}</th>
                            <th class="d-none">Mosque ID</th>
                            <th>{{ __('cashflow.date') }}</th>
                            <th>{{ __('cashflow.category') }}</th>
                            <th>{{ __('cashflow.description') }}</th>
                            <th>{{ __('cashflow.income') }}</th>
                            <th>{{ __('cashflow.expenses') }}</th>
                            <th>{{ __('cashflow.photo') }}</th>
                            <th>{{ __('cashflow.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashflow as $item)
                        @php
                        $formattedDate = formatDate($item->date);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="d-none">{{ $item->mosque_id }}</td>
                            <td>{!! $formattedDate !!}</td>
                            <td>{{ __('cashflow.'.strtolower($item->category)) }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>{{ $item->type == 'income' ? formatRM($item->amount) : '-' }}</td>
                            <td>{{ $item->type == 'expenses' ? formatRM($item->amount) : '-' }}</td>
                            <td>
                                @if($item->photo)
                                    <img src="{{ $item->photo }}" alt="{{ __('cashflow.photo') }}" width="50" height="50">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('cashflow.show', $item->id) }}" class="btn btn-secondary mb-1">{{ __('cashflow.details') }}</a>
                                <a href="{{ route('cashflow.edit', $item->id) }}" class="btn btn-warning mb-1">{{ __('cashflow.edit') }}</a>
                                <form action="{{ route('cashflow.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger mb-1">{{ __('cashflow.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="font-size: 20px; border:none;">
                                <strong>{{ __('cashflow.total_income') }}: {{ formatRM($totalIncome) }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-size: 20px; border:none;">
                                <strong>{{ __('cashflow.total_expenses') }}: {{ formatRM($totalExpenses) }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="font-size: 20px; border:none;">
                                <strong>{{ __('cashflow.total_amount') }}: {{ formatRM($totalIncome - $totalExpenses) }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#cashflow-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                columnDefs: [
                    { orderable: false, targets: [7, 8] }
                ],
                "language": {
                    "url": "{{ app()->getLocale() === 'ms' ? 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/ms.json' : 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/en-GB.json' }}"
                }
            });
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
