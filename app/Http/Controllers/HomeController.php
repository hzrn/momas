<?php

namespace App\Http\Controllers;

use App\Charts\CashflowChart;
use App\Models\Cashflow;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(CashflowChart $chart)
    {
        $chart = $chart->build();

        // Retrieve total income and expenses for the specific mosque
        $mosqueId = auth()->user()->mosque_id; // Adjust this to get the correct mosque id

        // Get the current month range
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get the last month's range
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Current month total income and expenses
        $currentMonthIncome = Cashflow::where('mosque_id', $mosqueId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $currentMonthExpenses = Cashflow::where('mosque_id', $mosqueId)
            ->where('type', 'expenses')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Last month total income and expenses
        $lastMonthIncome = Cashflow::where('mosque_id', $mosqueId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        $lastMonthExpenses = Cashflow::where('mosque_id', $mosqueId)
            ->where('type', 'expenses')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        // Calculate percentage change for income
        if ($lastMonthIncome > 0) {
            $incomePercentageChange = (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100;
        } else {
            $incomePercentageChange = $currentMonthIncome > 0 ? 100 : 0;
        }

        // Calculate percentage change for expenses
        if ($lastMonthExpenses > 0) {
            $expensesPercentageChange = (($currentMonthExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100;
        } else {
            $expensesPercentageChange = $currentMonthExpenses > 0 ? 100 : 0;
        }

        // Calculate total amount (net income)
        $totalIncome = Cashflow::where('mosque_id', $mosqueId)->where('type', 'income')->sum('amount');
        $totalExpenses = Cashflow::where('mosque_id', $mosqueId)->where('type', 'expenses')->sum('amount');
        $totalAmount = $totalIncome - $totalExpenses;

        // Calculate percentage change for total amount
        $lastMonthTotalIncome = Cashflow::where('mosque_id', $mosqueId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        $lastMonthTotalExpenses = Cashflow::where('mosque_id', $mosqueId)
            ->where('type', 'expenses')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        $lastMonthTotalAmount = $lastMonthTotalIncome - $lastMonthTotalExpenses;

        if ($lastMonthTotalAmount > 0) {
            $totalAmountPercentageChange = (($totalAmount - $lastMonthTotalAmount) / $lastMonthTotalAmount) * 100;
        } else {
            $totalAmountPercentageChange = $totalAmount > 0 ? 100 : 0;
        }

        // Fetch the latest cashflow entries (e.g., limit to the last 5)
        $latestCashflows = Cashflow::where('mosque_id', $mosqueId)->latest()->get();

        // Pass data to view
        return view('home', compact(
            'currentMonthIncome',
            'currentMonthExpenses',
            'incomePercentageChange',
            'expensesPercentageChange',
            'totalAmount',
            'totalAmountPercentageChange',
            'latestCashflows',
            'chart'
        ));
    }

}
