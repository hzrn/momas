@extends('layouts.app_adminkit')

@section('content')
<div class="container mt-3">
    <h1 class="h3 mb-3">{{ $title }}</h1>

    <!-- Year Selector for Line Chart -->
    <div class="row mb-4">
        <div class="col-sm-4">
            <label for="yearSelector">{{ __('item.select_year') }}</label>
            <select id="yearSelector" class="form-control" onchange="updateLineChart()">
                @for($year = now()->year; $year >= now()->year - 5; $year--)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Line Chart Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="lineChartTitle">{{ __('item.yearly_item_analysis') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Year and Month Selector for Pie Chart -->
    <div class="row mb-4">
        <div class="col-sm-4">
            <label for="yearSelectorPie">{{ __('item.select_year') }}</label>
            <select id="yearSelectorPie" class="form-control">
                @for($year = now()->year; $year >= now()->year - 5; $year--)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-sm-4">
            <label for="monthSelectorPie">{{ __('item.select_month') }}</label>
            <select id="monthSelectorPie" class="form-control" onchange="updatePieChart()">
                <option value="">{{ __('item.all_months') }}</option>
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}">{{ __('cashflow.' . str_pad($month, 2, '0', STR_PAD_LEFT)) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Pie Chart Section -->
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" id="pieChartTitle">{{ __('item.category_distribution') }}</h5>
                </div>
                <div class="card-header">
                    <h5 class="card-title" id="totalItemCount">{{ __('item.total_item') }}:</h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" ></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Apply general styles to all canvas elements */
        canvas {
            width: 98% !important;
            height: auto !important;
        }
    </style>


    <script>


        const predefinedColors = [
            '#FF6F61', '#6B5B93', '#88B04B', '#F7CAC9', '#92A8D1',
            '#955251', '#B9B3D1', '#F6B93B', '#F25F5C', '#4D4C7D',
            '#6A0572', '#F2A900', '#A4D65E', '#F1C40F', '#E74C3C',
            '#2ECC71', '#3498DB', '#E67E22', '#9B59B6', '#34495E',
            '#BDC3C7', '#7F8C8D', '#ECF0F1', '#D35400', '#1F618D',
            '#F5B041', '#A569BD', '#45B39D', '#52BE80', '#AAB7B8'
        ];

        let lineChart = null;
        let pieChart = null;

        // Update Line Chart Data
        function updateLineChart() {
            const year = document.getElementById('yearSelector').value;
            fetchLineChartData(year);
        }

        // Update Pie Chart Data
        function updatePieChart() {
            const year = document.getElementById('yearSelectorPie').value;
            const month = document.getElementById('monthSelectorPie').value;

            const params = new URLSearchParams({ year, month });

            fetch(`{{ route('item.piechart') }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    const pieChartTitle = document.getElementById('pieChartTitle');
                    const totalItemCount = document.getElementById('totalItemCount');


                    pieChartTitle.textContent = `@lang('item.category_distribution') - ${data.month} ${year}`;

                     // Update Total Itemrmation Count
                    totalItemCount.textContent = `@lang('item.total_item') - ${data.totalEntries}`;

                    if (pieChart) {
                        pieChart.destroy();
                    }

                    const ctx = document.getElementById('pieChart').getContext('2d');
                    pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.values,
                                backgroundColor: data.labels.map((label, index) => predefinedColors[index % predefinedColors.length])
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.parsed;

                                            const total = context.dataset.data.reduce((acc, curr) => {
                                                const numValue = Number(curr);
                                                return acc + numValue;
                                            }, 0);

                                            const percentage = total > 0
                                                ? ((value / total) * 100).toFixed(2)
                                                : '0.00';

                                            return `${context.label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })

        }

        // Fetch Line Chart Data
        function fetchLineChartData(year) {
            const params = new URLSearchParams({ year });

            fetch(`{{ route('item.linechart') }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const lineChartTitle = document.getElementById('lineChartTitle');
                    lineChartTitle.textContent = `@lang('item.yearly_item_analysis') - ${year}`;

                    if (lineChart) {
                        lineChart.destroy();
                    }

                    const ctx = document.getElementById('lineChart').getContext('2d');
                    lineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.months,
                            datasets: [{
                                label: '{{ __('item.total_item') }}',
                                data: data.totals,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 2,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            return Number.isInteger(value) ? value : '';
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.dataset.label}: ${context.parsed.y}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching line chart data:', error);
                    alert('Failed to fetch data. Please try again.');
                });
        }

        // Initial Fetch on Page Load
        document.addEventListener('DOMContentLoaded', () => {
            // Load Line Chart Data
            const lineChartYear = document.getElementById('yearSelector').value;
            fetchLineChartData(lineChartYear);

            // Load Pie Chart Data
            const pieChartYear = document.getElementById('yearSelectorPie').value;
            updatePieChart(pieChartYear);

            // Attach Event Listeners
            document.getElementById('yearSelectorPie').addEventListener('change', updatePieChart);
        });


    </script>
</div>
@endsection
