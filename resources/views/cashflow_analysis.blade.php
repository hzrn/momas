@extends('layouts.app_adminkit')

@section('content')
<div class="container mt-3">
    <h1 class="h3 mb-3">{{ __('cashflow.analysis_title') }}</h1>

    <!-- Year Selector for Line Chart -->
    <div class="row mb-4">
        <div class="col-sm-4">
            <label for="yearSelector">{{__('cashflow.select_year')}}</label>
            <select id="yearSelector" class="form-control">
                @for($year = now()->year; $year >= now()->year - 5; $year--)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Line Chart Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="lineChartTitle">{{ __('cashflow.yearly_cashflow_analysis') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Cashflow Chart Section -->
    <div class="row mb-4">
        <div class="col-sm-4">
            <label for="dailyMonthSelector">{{ __('cashflow.select_month') }}</label>
            <select id="dailyMonthSelector" class="form-control">
                @foreach(getMonthNames() as $monthNum => $monthName)
                    <option value="{{ $monthNum }}"
                        {{ $monthNum == now()->format('m') ? 'selected' : '' }}>
                        {{ $monthName }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Daily Line Chart Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="dailyLineChartTitle">{{ __('cashflow.daily_cashflow_analysis') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyLineChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Year and Month Selector for Pie Chart -->
    <div class="row mb-4">
        <div class="col-sm-4">
            <label for="yearSelectorPie">{{ __('cashflow.select_year') }}</label>
            <select id="yearSelectorPie" class="form-control">
                @for($year = now()->year; $year >= now()->year - 5; $year--)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-sm-4">
            <label for="monthSelectorPie">{{ __('cashflow.select_month') }}</label>
            <select id="monthSelectorPie" class="form-control">
                <option value="">{{ __('cashflow.all_months') }}</option>
                @foreach(getMonthNames() as $monthNum => $monthName)
                    <option value="{{ $monthNum }}">
                        {{ $monthName }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Pie Chart Section -->
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="pieChartTitle">Income vs Expenses</h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="card-title text-center" id="summaryTitle">Summary</h5>
                </div>
                <div class="card-body text-justify" id="yearlySummary">
                    <!-- Summary will be dynamically populated -->
                </div>
            </div>
        </div>
    </div>


    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        canvas {
            width: 99% !important;
            height: auto ;

    }</style>

    <script>
        let lineChart = null;

        function fetchLineChartData(year) {
            const params = new URLSearchParams({ year });

            fetch(`{{ route('cashflow.linechart') }}?${params}`)
                .then(response => response.json())
                .then(data => {

                    const lineChartTitle = document.getElementById('lineChartTitle');
                    lineChartTitle.textContent = `@lang('cashflow.yearly_cashflow_analysis') - ${year}`;

                    if (lineChart) {
                        lineChart.destroy();
                    }

                    const ctx = document.getElementById('lineChart').getContext('2d');
                    lineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(data.chartData),
                            datasets: [
                                {
                                    label: '@lang('cashflow.income')',
                                    data: Object.values(data.chartData).map(item => item.income),
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderWidth: 2,
                                    fill: false
                                },
                                {
                                    label: '@lang('cashflow.expenses')',
                                    data: Object.values(data.chartData).map(item => item.expenses),
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderWidth: 2,
                                    fill: false
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __('cashflow.amount') }} (RM)', // Use Blade syntax to echo the translated text
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.parsed.y;
                                            return `${context.dataset.label}: RM ${value.toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    updateSummary(data);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const yearSelector = document.getElementById('yearSelector');
            fetchLineChartData(yearSelector.value);

            yearSelector.addEventListener('change', function() {
                fetchLineChartData(this.value);
            });
        });


        function updateSummary(data) {
            const summaryContainer = document.getElementById('lineSummary');
            const netCashflow = data.totalIncome - data.totalExpenses;

            summaryContainer.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6>Total Income</h6>
                        <p class="text-success font-weight-bold">RM ${data.totalIncome.toFixed(2)}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <h6>Total Expenses</h6>
                        <p class="text-danger font-weight-bold">RM ${data.totalExpenses.toFixed(2)}</p>
                    </div>
                    <div class="col-12">
                        <h6>Net Cashflow</h6>
                        <p class="${netCashflow >= 0 ? 'text-success' : 'text-danger'} font-weight-bold">
                            RM ${netCashflow.toFixed(2)}
                        </p>
                    </div>
                </div>
            `;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const yearSelector = document.getElementById('yearSelector');

            fetchLineChartData(yearSelector.value);

            monthSelector.addEventListener('change', function() {
                fetchLineChartData(yearSelector.value, this.value);
            });
        });
    </script>

    <script>
        let dailyLineChart = null;

        document.addEventListener('DOMContentLoaded', function () {
            // Pass translated month names dynamically from the helper function
            const monthNames = {!! json_encode(getMonthNames()) !!};

            // Fetch the data for the current month when the page loads
            fetchDailyCashflowData(new Date().getMonth() + 1, monthNames);

            // Set up event listener for month selection change
            document.getElementById('dailyMonthSelector').addEventListener('change', function () {
                const selectedMonth = this.value; // Get the selected month
                fetchDailyCashflowData(selectedMonth, monthNames);
            });
        });


        function fetchDailyCashflowData(month, monthNames) {
            const year = new Date().getFullYear(); // Get the current year
            const params = new URLSearchParams({ year, month });

            fetch(`{{ route('cashflow.getDailyCashflow') }}?${params}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {

                    // Update chart title
                    const dailyLineChartTitle = document.getElementById('dailyLineChartTitle');
                    dailyLineChartTitle.textContent = `{{ __('cashflow.daily_cashflow_analysis') }} - ${monthNames[month - 1]} ${year}`;

                    // Destroy old chart if it exists
                    if (dailyLineChart) {
                        dailyLineChart.destroy();
                    }

                    // Reinitialize the chart with the new data
                    const ctx = document.getElementById('dailyLineChart').getContext('2d');
                    dailyLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(data.chartData), // Dates
                            datasets: [
                                {
                                    label: '{{ __('cashflow.daily_cashflow') }}', // Translated label
                                    data: Object.values(data.chartData), // Combined totals
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderWidth: 2,
                                    fill: false
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: '{{ __('cashflow.amount') }} (RM)', // Use Blade syntax to echo the translated text
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            const value = context.parsed.y;
                                            const label = value >= 0 ? '{{ __('cashflow.income') }}' : '{{ __('cashflow.expenses') }}';
                                            return `${label}: RM ${Math.abs(value).toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
        }
    </script>

    <script>
        // Global variables to store chart
        let pieChart = null;

        // Function to fetch and render pie chart data
        function fetchPieChartData(year, month = '') {
            const params = new URLSearchParams({
                year: year,
                month: month
            });

            fetch(`{{ route('cashflow.piechart') }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    // Update chart title
                    const pieChartTitle = document.getElementById('pieChartTitle');
                    const summaryTitle = document.getElementById('summaryTitle');

                    pieChartTitle.textContent = month
                        ? `${getTranslation('cashflow.income_vs_expenses_by_category')} - ${getMonthName(month)} ${year}`
                        : `${getTranslation('cashflow.yearly_income_vs_expenses')} - ${year}`;

                    summaryTitle.textContent = month
                        ? `${getTranslation('cashflow.summary')} - ${getMonthName(month)} ${year}`
                        : `${getTranslation('cashflow.yearly_summary')} - ${year}`;

                    // Destroy existing chart
                    if (pieChart) {
                        pieChart.destroy();
                    }

                    // Render new pie chart with categories
                    const ctx = document.getElementById('pieChart').getContext('2d');
                    pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.values,
                                backgroundColor: data.labels.map((label, index) =>
                                    index % 2 === 0 ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)'
                                )
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.parsed;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = (value / total * 100).toFixed(2);
                                            return `${getTranslation('cashflow.currency')} ${value.toFixed(2)} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Update summary
                    updateSummary(data);
                });
        }

        // Function to update summary
        function updateSummary(data) {
            const summaryContainer = document.getElementById('yearlySummary');
            const netCashflow = data.totalIncome - data.totalExpenses;

            let summaryHtml = `
                <div class="row">
                    <div class="col-12 mb-3 text-center">
                        <h5>${getTranslation('cashflow.total_income')}</h5>
                        <p class="text-success font-weight-bold">${getTranslation('cashflow.currency')} ${data.totalIncome.toFixed(2)}</p>
                    </div>
                    <div class="col-12 mb-3 text-center">
                        <h5>${getTranslation('cashflow.total_expenses')}</h5>
                        <p class="text-danger font-weight-bold">${getTranslation('cashflow.currency')} ${data.totalExpenses.toFixed(2)}</p>
                    </div>
                    <div class="col-12 text-center">
                        <h5>${getTranslation('cashflow.net_cashflow')}</h5>
                        <p class="${netCashflow >= 0 ? 'text-success' : 'text-danger'} font-weight-bold">
                            ${getTranslation('cashflow.currency')} ${netCashflow.toFixed(2)}
                        </p>
                    </div>
                </div>
            `;

            if (data.categories) {

                summaryHtml += `<div class="row mt-4 text-center"><h5>${getTranslation('cashflow.category_breakdown')}</h5></div>`;
                summaryHtml += `<div class="row">`;

                // Income categories
                summaryHtml += `<div class="col-6 text-center">
                    <h6 class="pb-2">${getTranslation('cashflow.income_categories')}</h6>
                    <ul class="list-unstyled">`;

                (data.categories.income || []).forEach(category => {
                    const translatedCategory = getTranslation('cashflow.' + category.category.toLowerCase().replace(/\s+/g, '_'));
                    summaryHtml += `<li class="pb-1">${translatedCategory}: ${getTranslation('cashflow.currency')} ${parseFloat(category.total).toFixed(2)}</li>`;
                });

                summaryHtml += `</ul></div>`;

                // Expenses categories
                summaryHtml += `<div class="col-6 text-center">
                    <h6 class="pb-2">${getTranslation('cashflow.expenses_categories')}</h6>
                    <ul class="list-unstyled">`;

                (data.categories.expenses || []).forEach(category => {
                    const translatedCategory = getTranslation('cashflow.' + category.category.toLowerCase().replace(/\s+/g, '_'));
                    summaryHtml += `<li class="pb-1">${translatedCategory}: ${getTranslation('cashflow.currency')} ${parseFloat(category.total).toFixed(2)}</li>`;
                });

                summaryHtml += `</ul></div>`;
            }

            summaryContainer.innerHTML = summaryHtml;
        }

            // Helper function to get month name
            function getMonthName(month) {
                // Pass translated month names dynamically using the helper function
                const monthNames = {!! json_encode(array_values(getMonthNames())) !!};
                return monthNames[parseInt(month, 10) - 1];
            }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            const yearSelectorPie = document.getElementById('yearSelectorPie');
            const monthSelectorPie = document.getElementById('monthSelectorPie');

            // Initial load of pie chart for current year
            fetchPieChartData(yearSelectorPie.value);

            // Year selector event listener
            yearSelectorPie.addEventListener('change', function() {
                fetchPieChartData(this.value, monthSelectorPie.value);
            });

            // Month selector event listener
            monthSelectorPie.addEventListener('change', function() {
                fetchPieChartData(yearSelectorPie.value, this.value);
            });
        });

                // Helper function to get translations
                function getTranslation(key) {
                    const translations = {!! json_encode([
                        'cashflow.income_vs_expenses_by_category' => __('cashflow.income_vs_expenses_by_category'),
                        'cashflow.yearly_income_vs_expenses' => __('cashflow.yearly_income_vs_expenses'),
                        'cashflow.summary' => __('cashflow.summary'),
                        'cashflow.yearly_summary' => __('cashflow.yearly_summary'),
                        'cashflow.total_income' => __('cashflow.total_income'),
                        'cashflow.total_expenses' => __('cashflow.total_expenses'),
                        'cashflow.net_cashflow' => __('cashflow.net_cashflow'),
                        'cashflow.category_breakdown' => __('cashflow.category_breakdown'),
                        'cashflow.income_categories' => __('cashflow.income_categories'),
                        'cashflow.expenses_categories' => __('cashflow.expenses_categories'),
                        'cashflow.currency' => __('cashflow.currency'),
                        // Income categories
                        'cashflow.sadaqah' => __('cashflow.sadaqah'),
                        'cashflow.donation' => __('cashflow.donation'),
                        'cashflow.zakat' => __('cashflow.zakat'),
                        'cashflow.waqf' => __('cashflow.waqf'),
                        'cashflow.fitrah' => __('cashflow.fitrah'),
                        'cashflow.general' => __('cashflow.general'),
                        // Expense categories
                        'cashflow.utilities' => __('cashflow.utilities'),
                        'cashflow.maintenance' => __('cashflow.maintenance'),
                        'cashflow.salaries' => __('cashflow.salaries'),
                        'cashflow.supplies' => __('cashflow.supplies'),
                        'cashflow.events' => __('cashflow.events'),
                        'cashflow.insurance' => __('cashflow.insurance'),
                        'cashflow.miscellaneous' => __('cashflow.miscellaneous'),
                    ]) !!};

                    // Check if a translation exists for the given key
                    return translations[key] || key;  // If no translation exists, return the key itself
                }
    </script>


</div>
@endsection
