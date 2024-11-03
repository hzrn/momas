@extends('layouts.app_adminkit')

@section('CashflowChartjs')
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection

@section('content')
<div class="container-fluid p-0">

    <h1 class="h3 mb-3">Mosque Dashboard</h1>

    <div class="row">

<div class="row">
    <!-- Total Income Card -->
    <div class="col-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title ">Total Income</h5>
                    </div>
                    <div class="col-auto">
                        <i class="align-middle" data-feather="arrow-up-circle"></i>
                    </div>
                </div>
                <h1 class="mt-1 mb-3 text-success">{{ formatRM($currentMonthIncome) }}</h1>
                <span> {{ number_format($incomePercentageChange, 2) }}% </span>
                <span>Since last month</span>
            </div>
        </div>
    </div>

    <!-- Total Expenses Card -->
    <div class="col-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Total Expenses</h5>
                    </div>
                    <div class="col-auto">
                        <i class="align-middle" data-feather="arrow-down-circle"></i>
                    </div>
                </div>
                <h1 class="mt-1 mb-3 text-danger">{{ formatRM($currentMonthExpenses) }}</h1>
                <span> {{ number_format($expensesPercentageChange, 2) }}% </span>
                <span>Since last month</span>
            </div>
        </div>
    </div>

    <!-- Total Amount (Net) Card -->
    <div class="col-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Total Net Amount</h5>
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
                <span>Since last month</span>
            </div>
        </div>
    </div>
</div>


        <!-- Cashflow Chart -->
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Cashflow Table -->
    <div class="row d-none">
        <div class="col-12 col-lg-8 col-xxl-9 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Cashflow</h5>
                </div>
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="d-none d-xl-table-cell">Date</th>
                            <th class="d-none d-xl-table-cell">Type</th>
                            <th class="d-none d-md-table-cell">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestCashflows as $cashflow)
                        <tr>
                            <td>{{ $cashflow->category ?? 'No Category' }}</td>
                            <td class="d-none d-xl-table-cell">{{ $cashflow->date->format('d/m/Y') }}</td>
                            <td class="d-none d-xl-table-cell">{{ ucfirst($cashflow->type) }}</td>
                            <td class="d-none d-md-table-cell">{{ formatRM($cashflow->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Sales Chart -->
        <div class="col-12 col-lg-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Sales</h5>
                </div>
                <div class="card-body d-flex w-100">
                    <div class="align-self-center chart chart-lg">
                        <canvas id="chartjs-dashboard-bar"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
