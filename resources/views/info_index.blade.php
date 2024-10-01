@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>
<a href="{{route('info.create')}}" class="btn btn-primary mb-3">Add {{$title}}</a>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Add table-responsive div -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date and Time</th>
                                <th>Content</th>
                                <th>Created by</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($info as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->category->name ?? 'N/A'}}</td>
                                    <td>{{$item->date}}</td>
                                    <td>{{strip_tags($item->content)}}</td>
                                    <td>{{($item->createdBy)->name}}</td>
                                    <td>
                                        <a href="{{ route('info.edit', $item->id) }}" class="btn btn-warning mb-1">Edit</a>
                                        <form action="{{ route('info.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mb-1">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End table-responsive div -->
            </div>
        </div>
    </div>
</div>
@endsection
