@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{$title}}</h1>
    <a href="{{ route('profile.create') }}" class="btn btn-primary mb-3">Add {{$title}}</a>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="d-none">Mosque ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Content</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($profile as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-none">{{ $item->mosque_id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->content }}</td>
                        <td>
                            <a href="{{ route('profile.show', $item->id) }}" class="btn btn-secondary mb-1">Details</a>
                            <a href="{{ route('profile.edit', $item->id) }}" class="btn btn-warning mb-1">Edit</a>
                            <form action="{{ route('profile.destroy', $item->id) }}" method="POST" style="display:inline-block;">
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

{{ $profile->links() }}
@endsection
