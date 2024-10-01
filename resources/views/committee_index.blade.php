@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>
<a href="{{route('committee.create')}}" class="btn btn-primary mb-3">Add {{$title}}</a>


<div class="row">
    <div class="col-12">
        <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Position</th>
                                <th>Address</th>
                                <th>Photo</th>
                                <th>Action</th>
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
                                            <img src="{{ asset('storage/committees/' . $item->photo) }}" alt="Photo" width="50" height="50">
                                        @else
                                            No Photo
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('committee.show', $item->id) }}" class="btn btn-secondary mb-1">Details</a>
                                        <a href="{{ route('committee.edit', $item->id) }}" class="btn btn-warning mb-1">Edit</a>
                                        <form action="{{ route('committee.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mb-1">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            {{ $committee->links() }}
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
