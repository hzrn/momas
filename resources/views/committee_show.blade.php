@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $committee->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Position:</strong></td>
                        <td>{{ $committee->position }}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone Number:</strong></td>
                        <td>{{ $committee->phone_num }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>{{ $committee->address }}</td>
                    </tr>
                    <tr>
                        <td><strong>Photo:</strong></td>
                        <td>
                            @if ($committee->photo)
                                <img src="{{ asset('storage/committees/' . $committee->photo) }}" alt="{{ $committee->name }}" width="100" height="100">
                            @else
                                No Photo
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created by:</strong></td>
                        <td>{{ ($committee->createdBy)->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated by:</strong></td>
                        <td>{{ optional($committee->updatedBy)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created at:</strong></td>
                        <td>{{ $committee->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated at:</strong></td>
                        <td>
                            @if ($committee->created_at->eq($committee->updated_at))
                                -
                            @else
                                {{ $committee->updated_at->format('d-m-Y H:i') }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('committee.index') }}" class="btn btn-secondary mt-3">Back to Committee List</a>
        </div>
    </div>

@endsection
