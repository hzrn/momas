@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>
<a href="{{route('item.create')}}" class="btn btn-primary mb-3">Add {{$title}}</a>

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
                                <th>Category</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Created by</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->category->name ?? 'N/A'}}</td>
                                    <td>{{strip_tags($item->description)}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{formatRM($item->price)}}</td>
                                    <td>{{($item->createdBy)->name}}</td>
                                    <td>
                                        <a href="{{ route('item.edit', $item->id) }}" class="btn btn-warning mb-1">Edit</a>
                                        <form action="{{ route('item.destroy', $item->id) }}" method="POST" style="display:inline-block;">
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
            </div>
        </div>
    </div>
</div>
@endsection
