<?php

namespace App\Charts;

use App\Models\Cashflow;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class CashflowChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $year = date('Y');
        $monthlyTotals = [];
        $months = [];

        // Loop through each month of the current year to calculate monthly totals
        for ($i = 1; $i <= 12; $i++) {
            // Get the start and end date of the current month
            $startOfMonth = Carbon::createFromDate($year, $i, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $i, 1)->endOfMonth();

            // Calculate total income and total expenses for the current month
            $monthlyIncome = Cashflow::MosqueUser()
                ->where('type', 'income')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $monthlyExpenses = Cashflow::MosqueUser()
                ->where('type', 'expenses')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            // Calculate net cash flow (income - expenses) for the month
            $monthlyTotal = $monthlyIncome - $monthlyExpenses;

            // Store the result in the monthlyTotals array
            $monthlyTotals[] = $monthlyTotal;
            // Store the month name for the X-axis
            $months[] = $startOfMonth->format('F');
        }

        return $this->chart->lineChart()
            ->setTitle('Monthly Cashflow')
            ->setHeight(300)
            ->addData('Net Cashflow', $monthlyTotals)  // Net cashflow per month
            ->setXAxis($months);  // Months for the X-axis
    }
}
