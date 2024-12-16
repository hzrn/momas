@extends('layouts.app_adminkit')

@section('content')

    <h1 class="h3 mb-3">{{ $title }}</h1>

    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>{{ __('cashflow.date') }}:</strong></td>
                        <td>{!! formatDate($cashflow->date) !!}</td> <!-- Applying formatDate here -->
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.category') }}:</strong></td>
                        <td>{{ __('cashflow.' . strtolower($cashflow->category)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.description') }}:</strong></td>
                        <td>{{ $cashflow->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.type') }}:</strong></td>
                        <td>
                            @if($cashflow->type === 'expenses')
                                {{ __('cashflow.expenses') }}
                            @elseif($cashflow->type === 'income')
                                {{ __('cashflow.income') }}
                            @else
                                {{ $cashflow->type }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td><strong>{{ __('cashflow.amount') }}:</strong></td>
                        <td>{{ formatRM($cashflow->amount) }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.photo') }}:</strong></td>
                        <td>
                            @if ($cashflow->photo)
                                <!-- Clickable image to open the modal -->
                                <img src="{{ asset('storage/cashflows/' . $cashflow->photo) }}"
                                     alt="{{ $cashflow->name }}"
                                     width="100" height="100"
                                     data-bs-toggle="modal" data-bs-target="#photoModal"
                                     style="cursor: pointer;">
                            @else
                                {{ __('cashflow.no_photo') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.created_by') }}:</strong></td>
                        <td>{{ $cashflow->createdBy->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.updated_by') }}:</strong></td>
                        <td>{{ optional($cashflow->updatedBy)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.created_at') }}:</strong></td>
                        <td>{!! formatDate($cashflow->created_at) !!}</td> <!-- Formatting created_at here -->
                    </tr>
                    <tr>
                        <td><strong>{{ __('cashflow.updated_at') }}:</strong></td>
                        <td>
                            @if ($cashflow->created_at->eq($cashflow->updated_at))
                                {{ __('cashflow.not_updated') }}
                            @else
                                {!! formatDate($cashflow->updated_at) !!} <!-- Formatting updated_at here -->
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('cashflow.index') }}" class="btn btn-secondary mt-3">
                {{ __('cashflow.back_to_list') }}
            </a>
        </div>
    </div>

    <!-- Bootstrap Modal for Enlarged Photo -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/cashflows/' . $cashflow->photo) }}"
                         alt="{{ $cashflow->name }}"
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>

@endsection
