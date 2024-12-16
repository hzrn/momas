
@extends('layouts.app_adminkit')
@section('content')

@include('partials.latest_income_modal')
@include('partials.latest_expenses_modal')
@include('partials.latest_committee_modal')
@include('partials.latest_info_modal')
@include('partials.latest_item_modal')

<div class="container-fluid p-0">

    <h1 class="h3 mb-3">{{__('home.mosque_dashboard')}}</h1>

    <!-- Prayer Times Card -->
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('home.prayer_times') }}</h5>
                <div class="row text-center">
                    @foreach(array_intersect_key($timings, array_flip(['Imsak', 'Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'])) as $prayer => $time)
                        @if($prayer === 'Fajr')
                            @php
                                // Add 10 minutes to Fajr time
                                $fajrTime = \Carbon\Carbon::createFromFormat('H:i', $time)->addMinutes(10)->format('H:i');
                            @endphp
                        @endif
                        <div class="col-2">
                            <div class="icon {{ strtolower($prayer) }}" style="font-size: 24px; margin-bottom: 10px;">
                                <!-- Feather icons for each prayer -->
                                @switch($prayer)
                                    @case('Imsak') <i data-feather="moon" class="text-primary"></i> @break
                                    @case('Fajr') <i data-feather="sunrise" class="text-primary"></i> @break
                                    @case('Dhuhr') <i data-feather="sun" class="text-warning"></i> @break
                                    @case('Asr') <i data-feather="sun" class="text-orange"></i> @break
                                    @case('Maghrib') <i data-feather="sunset" class="text-danger"></i> @break
                                    @case('Isha') <i data-feather="moon" class="text-black"></i> @break
                                @endswitch
                            </div>
                            <div class="name" style="font-weight: bold;">{{ ucfirst($prayer) }}</div>
                            <div class="time text-muted">
                                {{ $prayer === 'Fajr' ? $fajrTime : $time }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Income Card -->
        <div class="col-4 d-flex">
            <!-- Card acts as button -->
            <div class="card flex-fill" data-bs-toggle="modal" data-bs-target="#latestIncomeModal" style="cursor: pointer;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('home.total_income') }}</h5>
                        </div>
                        <div class="col-auto">
                            <i class="align-middle text-success" data-feather="arrow-up-circle"></i>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3 text-success">{{ formatRM($currentMonthIncome) }}</h1>
                    <span class="text-muted">{{ number_format($incomePercentageChange, 2) }}% {{ __('home.since_last_month') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Expenses Card -->
        <div class="col-4 d-flex">
            <div class="card flex-fill" data-bs-toggle="modal" data-bs-target="#latestExpensesModal" style="cursor: pointer;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{__('home.total_expenses')}}</h5>
                        </div>
                        <div class="col-auto">
                            <i class="align-middle" data-feather="arrow-down-circle"></i>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3 text-danger">{{ formatRM($currentMonthExpenses) }}</h1>
                    <span>{{ number_format($expensesPercentageChange, 2) }}%</span>
                    <span>{{__('home.since_last_month')}}</span>
                </div>
            </div>
        </div>

        <!-- Total Amount (Net) Card -->
        <div class="col-4 d-flex">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{__('home.total_net_amount')}}</h5>
                        </div>
                        <div class="col-auto">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3 text-primary">{{ formatRM($totalAmount) }}</h1>
                    <span class="{{ $totalAmountPercentageChange >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="mdi mdi-arrow-{{ $totalAmountPercentageChange >= 0 ? 'up' : 'down' }}"></i>
                        {{ number_format($totalAmountPercentageChange, 2) }}%
                    </span>
                    <span>{{__('home.since_last_month')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Committees Card -->
        <div class="col-4 d-flex">
            <div class="card flex-fill" data-bs-toggle="modal" data-bs-target="#latestCommitteeModal" style="cursor: pointer;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{__('home.total_committees')}}</h5>
                        </div>
                        <div class="col-auto">
                            <i class="align-middle" data-feather="users"></i>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3" style="color: #1a4e89">{{ $totalCommittees }}</h1>
                    <span>{{__('home.committees_desc')}}</span>
                </div>
            </div>
        </div>

        <!-- Total Information Card -->
        <div class="col-4 d-flex">
            <div class="card flex-fill" data-bs-toggle="modal" data-bs-target="#latestInfoModal" style="cursor: pointer;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{__('home.total_info')}}</h5>
                        </div>
                        <div class="col-auto">
                            <i class="align-middle" data-feather="file-text"></i>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3" style="color: #1a4e89">{{ $totalInfo }}</h1>
                    <span>{{__('home.info_desc')}}</span>
                </div>
            </div>
        </div>

        <!-- Total Items Card -->
        <div class="col-4 d-flex">
            <div class="card flex-fill" data-bs-toggle="modal" data-bs-target="#latestItemModal" style="cursor: pointer;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{__('home.total_items')}}</h5>
                        </div>
                        <div class="col-auto">
                            <i class="align-middle" data-feather="archive"></i>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3" style="color: #1a4e89">{{ $totalItems }}</h1>
                    <span>{{__('home.items_desc')}}</span>
                </div>
            </div>
        </div>
    </div>





</div>

<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

@endsection
