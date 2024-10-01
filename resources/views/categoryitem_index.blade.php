@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>
<a href="{{route('categoryitem.create')}}" class="btn btn-primary mb-3">Add {{$title}}</a>

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
                                <th>Created by</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoryitem as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{optional($item->createdBy)->name}}</td>
                                    <td>
                                        <a href="{{ route('categoryitem.edit', $item->id) }}" class="btn btn-warning mb-1">Edit</a>
                                        <form action="{{ route('categoryitem.destroy', $item->id) }}" method="POST" style="display:inline-block;">
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
