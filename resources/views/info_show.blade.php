@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>{{ __('info.title') }}:</strong></td>
                        <td>{{ $info->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.category') }}:</strong></td>
                        <td>{{ $info->category->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.date') }}:</strong></td>
                        <td>{!! formatDate($info->date) !!}</td>  <!-- Apply formatDate helper here -->
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.description') }}:</strong></td>
                        <td>{{ $info->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.photo') }}:</strong></td>
                        <td>
                            @if ($info->photo)
                                <!-- Clickable image to open the modal -->
                                <img src="{{ $info->photo }}"
                                     alt="{{ $info->name }}"
                                     width="auto" height="100px"
                                     data-bs-toggle="modal" data-bs-target="#photoModal"
                                     style="cursor: pointer;">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.created_by') }}:</strong></td>
                        <td>{{ $info->createdBy->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.updated_by') }}:</strong></td>
                        <td>{{ optional($info->updatedBy)->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td><strong>{{ __('info.created_at') }}:</strong></td>
                        <td>{!! formatDate($info->created_at) !!}</td> <!-- Apply formatDate helper here -->
                    </tr>
                    <tr>
                        <td><strong>{{ __('info.updated_at') }}:</strong></td>
                        <td>
                            @if ($info->created_at->eq($info->updated_at))
                                -
                            @else
                                {!! formatDate($info->updated_at) !!} <!-- Apply formatDate helper here -->
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>

            <a href="{{ route('info.index') }}" class="btn btn-secondary mt-3">{{ __('info.back_to_list') }}</a>

        </div>
    </div>

    <!-- Bootstrap Modal for Enlarged Photo -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{ $info->photo }}"
                         alt="{{ $info->name }}"
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>

@endsection
