@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>{{ __('committee.name') }}:</strong></td>
                        <td>{{ $committee->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.position') }}:</strong></td>
                        <td>{{ $committee->position }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.phone') }}:</strong></td>
                        <td>{{ $committee->phone_num }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.address') }}:</strong></td>
                        <td>{{ $committee->address }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.photo') }}:</strong></td>
                        <td>
                            @if ($committee->photo)
                                <!-- Clickable image to open the modal -->
                                <img src="{{ $committee->photo }}"
                                     alt="{{ $committee->name }}"
                                     width="auto" height="100px"
                                     data-bs-toggle="modal" data-bs-target="#photoModal"
                                     style="cursor: pointer;">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.created_by') }}:</strong></td>
                        <td>{{ $committee->createdBy->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.updated_by') }}:</strong></td>
                        <td>{{ optional($committee->updatedBy)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.created_at') }}:</strong></td>
                        <td>{!! formatDate($committee->created_at) !!}</td> <!-- Applying formatDate for created_at -->
                    </tr>
                    <tr>
                        <td><strong>{{ __('committee.updated_at') }}:</strong></td>
                        <td>
                            @if ($committee->created_at->eq($committee->updated_at))
                                {{ __('committee.not_updated') }}
                            @else
                                {!! formatDate($committee->updated_at) !!} <!-- Applying formatDate for updated_at -->
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('committee.index') }}" class="btn btn-secondary mt-3">
                {{ __('committee.back_to_list') }}
            </a>
        </div>
    </div>

    <!-- Bootstrap Modal for Enlarged Photo -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{ $committee->photo }}"
                         alt="{{ $committee->name }}"
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>

@endsection
