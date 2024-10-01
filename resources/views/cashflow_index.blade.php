@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{$title}}</h1>
    <a href="{{ route('cashflow.create') }}" class="btn btn-primary mb-3">Add {{$title}}</a>

    <!-- Date Filter Form -->
    <form action="{{ route('cashflow.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="End Date">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="d-none">Mosque ID</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Income</th>
                        <th>Expenses</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cashflow as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-none">{{ $item->mosque_id }}</td>
                        <td>{{ $item->date->translatedFormat('d-m-Y') }}</td>
                        <td>{{ $item->category ?? 'public' }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            {{ $item->type == 'income' ? formatRM($item->amount) : '-' }}
                        </td>
                        <td>
                            {{ $item->type == 'expenses' ? formatRM($item->amount) : '-' }}
                        </td>
                        <td>
                            @if($item->photo)
                                <img src="{{ asset('storage/cashflows/' . $item->photo) }}" alt="Photo" width="50" height="50">
                            @else
                                No Photo
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('cashflow.show', $item->id) }}" class="btn btn-secondary mb-1">Details</a>
                            <a href="{{ route('cashflow.edit', $item->id) }}" class="btn btn-warning mb-1">Edit</a>
                            <form action="{{ route('cashflow.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mb-1">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="font-size: 20px; border:none;"><strong>Total Income: {{ formatRM($totalIncome) }}</strong></td>
                        <td><strong style="font-size: 24px;"></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size: 20px; border:none;"><strong>Total Expenses: {{ formatRM($totalExpenses) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size: 20px; border:none;"><strong>Total Amount: {{ formatRM($totalIncome - $totalExpenses) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </div>

{{ $cashflow->links() }}
@endsection
