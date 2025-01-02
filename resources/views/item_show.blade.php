@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>{{__('item.name')}}:</strong></td>
                        <td>{{ $item->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.category')}}:</strong></td>
                        <td>{{ $item->category->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.description')}}:</strong></td>
                        <td>{{ $item->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.quantity')}}:</strong></td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.price')}}:</strong></td>
                        <td>{{ formatRM($item->price) }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.photo')}}:</strong></td>
                        <td>
                            @if ($item->photo)
                                <!-- Clickable image to open the modal -->
                                <img src="{{ $item->photo }}"
                                     alt="{{ $item->name }}"
                                     width="auto" height="100"
                                     data-bs-toggle="modal" data-bs-target="#photoModal"
                                     style="cursor: pointer;">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.created_by')}}:</strong></td>
                        <td>{{ $item->createdBy->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.updated_by')}}:</strong></td>
                        <td>{{ optional($item->updatedBy)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{__('item.created_at')}}:</strong></td>
                        <td>{!! formatDate($item->created_at) !!}</td> <!-- Applying formatDate for created_at -->
                    </tr>
                    <tr>
                        <td><strong>{{__('item.updated_at')}}:</strong></td>
                        <td>
                            @if ($item->created_at->eq($item->updated_at))
                                -
                            @else
                                {!! formatDate($item->updated_at) !!} <!-- Applying formatDate for updated_at -->
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('item.index') }}" class="btn btn-secondary mt-3">{{__('item.back_to_list')}}</a>
        </div>
    </div>

    <!-- Bootstrap Modal for Enlarged Photo -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{ $item->photo }}"
                         alt="{{ $item->name }}"
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>

@endsection
