@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>{{__('profile.title')}}:</strong></td>
                        <td>{{ $profile->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('profile.category')}}:</strong></td>
                        <td>{{ __('profile.' . strtolower($profile->category)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('profile.content')}}:</strong></td>
                        <td>{{ $profile->content ?? '-' }}</td> <!-- Added ?? '-' for handling null -->
                    </tr>
                    <tr>
                        <td><strong>{{__('profile.created_by')}}:</strong></td>
                        <td>{{ ($profile->createdBy)->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('profile.updated_by')}}:</strong></td>
                        <td>{{ optional($profile->updatedBy)->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>{{__('profile.created_at')}}:</strong></td>
                        <td>{!! formatDate($profile->created_at) !!}</td> <!-- Applying formatDate for created_at -->
                    </tr>
                    <tr>
                        <td><strong>{{__('profile.updated_at')}}:</strong></td>
                        <td>
                            @if ($profile->created_at->eq($profile->updated_at))
                                -
                            @else
                                {!! formatDate($profile->updated_at) !!} <!-- Applying formatDate for updated_at -->
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>

            <a href="{{ route('profile.index') }}" class="btn btn-secondary mt-3">{{__('profile.back_to_list')}}</a>
        </div>
    </div>

@endsection
