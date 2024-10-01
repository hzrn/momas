@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>Title:</strong></td>
                        <td>{{ $profile->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>Category:</strong></td>
                        <td>{{ $profile->category }}</td>
                    </tr>
                    <tr>
                        <td><strong>Content:</strong></td>
                        <td>{{ $profile->content }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created by:</strong></td>
                        <td>{{ ($profile->createdBy)->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated by:</strong></td>
                        <td>{{ optional($profile->updatedBy)->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>Created at:</strong></td>
                        <td>{{ $profile->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated at:</strong></td>
                        <td>
                            @if ($profile->created_at->eq($profile->updated_at))
                                -
                            @else
                                {{ $profile->updated_at->format('d-m-Y H:i') }}
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>

            <a href="{{ route('profile.index') }}" class="btn btn-secondary mt-3">Back to Mosque Profiles</a>
        </div>
    </div>

@endsection
