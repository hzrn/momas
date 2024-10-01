@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>Date:</strong></td>
                        <td>{{ $cashflow->date }}</td>
                    </tr>
                    <tr>
                        <td><strong>Category:</strong></td>
                        <td>{{ $cashflow->category }}</td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td>{{ $cashflow->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>{{ $cashflow->type }}</td>
                    </tr>
                    <tr>
                        <td><strong>Amount:</strong></td>
                        <td>{{ formatRM($cashflow->amount) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Photo:</strong></td>
                        <td>
                            @if ($cashflow->photo)
                                <!-- Clickable image that triggers modal -->
                                <img src="{{ asset('storage/cashflows/' . $cashflow->photo) }}" alt="{{ $cashflow->name }}" width="100" height="100" data-bs-toggle="modal" data-bs-target="#photoModal" style="cursor: pointer;">
                            @else
                                No Photo
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created by:</strong></td>
                        <td>{{ ($cashflow->createdBy)->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated by:</strong></td>
                        <td>{{ optional($cashflow->updatedBy)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created at:</strong></td>
                        <td>{{ $cashflow->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated at:</strong></td>
                        <td>
                            @if ($cashflow->created_at->eq($cashflow->updated_at))
                                -
                            @else
                                {{ $cashflow->updated_at->format('d-m-Y H:i') }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('cashflow.index') }}" class="btn btn-secondary mt-3">Back to Cashflow List</a>
        </div>
    </div>

    <!-- Bootstrap Modal for Enlarged Photo -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Photo Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Display the large photo inside the modal -->
                    <img src="{{ asset('storage/cashflows/' . $cashflow->photo) }}" alt="{{ $cashflow->name }}" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

@endsection
