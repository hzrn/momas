<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use App\Models\Committee;
use App\Models\Info;
use App\Models\Item;
use App\Models\Mosque;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
     * Provide default prayer times
     *
     * @return array
     */
    private function getDefaultPrayerTimes()
    {
        return [
            'Fajr' => '05:30',
            'Sunrise' => '06:45',
            'Dhuhr' => '12:30',
            'Asr' => '15:45',
            'Maghrib' => '19:15',
            'Isha' => '20:30'
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $title = __('home.dashboard');
    $mosqueId = auth()->user()->mosque_id;

    // Get mosque data with a single query
    $mosque = Cache::remember("mosque_{$mosqueId}", now()->addHours(24), function () use ($mosqueId) {
        return Mosque::find($mosqueId);
    });

    if (!$mosque) {
        return redirect()->route('home')->withErrors(['error' => 'Mosque not found']);
    }

    // Set date ranges
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
    $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

    // Get all cashflow data with a single query
    $cashflowData = Cache::remember("cashflow_data_{$mosqueId}", now()->addMinutes(5), function () use ($mosqueId, $startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth) {
        return Cashflow::selectRaw('
            SUM(CASE
                WHEN type = "income" AND date BETWEEN ? AND ? THEN amount
                ELSE 0
            END) as current_month_income,
            SUM(CASE
                WHEN type = "expenses" AND date BETWEEN ? AND ? THEN amount
                ELSE 0
            END) as current_month_expenses,
            SUM(CASE
                WHEN type = "income" AND date BETWEEN ? AND ? THEN amount
                ELSE 0
            END) as last_month_income,
            SUM(CASE
                WHEN type = "expenses" AND date BETWEEN ? AND ? THEN amount
                ELSE 0
            END) as last_month_expenses,
            SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = "expenses" THEN amount ELSE 0 END) as total_expenses
        ', [
            $startOfMonth, $endOfMonth,
            $startOfMonth, $endOfMonth,
            $startOfLastMonth, $endOfLastMonth,
            $startOfLastMonth, $endOfLastMonth
        ])
        ->where('mosque_id', $mosqueId)
        ->first();
    });

    // Calculate all percentages
    $currentMonthIncome = $cashflowData->current_month_income;
    $currentMonthExpenses = $cashflowData->current_month_expenses;
    $lastMonthIncome = $cashflowData->last_month_income;
    $lastMonthExpenses = $cashflowData->last_month_expenses;
    $totalIncome = $cashflowData->total_income;
    $totalExpenses = $cashflowData->total_expenses;

    $incomePercentageChange = $this->calculatePercentageChange($currentMonthIncome, $lastMonthIncome);
    $expensesPercentageChange = $this->calculatePercentageChange($currentMonthExpenses, $lastMonthExpenses);

    $totalAmount = $totalIncome - $totalExpenses;
    $lastMonthTotalAmount = $lastMonthIncome - $lastMonthExpenses;
    $totalAmountPercentageChange = $this->calculatePercentageChange($totalAmount, $lastMonthTotalAmount);

    // Get prayer times
    $timings = $this->getPrayerTimes($mosque);

    // Get counts and recent records with a single query each
    $totals = Cache::remember("totals_{$mosqueId}", now()->addMinutes(5), function () use ($mosqueId) {
        return [
            'totalCommittees' => Committee::where('mosque_id', $mosqueId)->count(),
            'totalInfo' => Info::where('mosque_id', $mosqueId)->count(),
            'totalItems' => Item::where('mosque_id', $mosqueId)->count()
        ];
    });

    // Get recent records with eager loading
    $recentRecords = Cache::remember("recent_records_{$mosqueId}", now()->addMinutes(5), function () use ($mosqueId) {
        return [
            'modalIncome' => Cashflow::where('mosque_id', $mosqueId)
                ->where('type', 'income')
                ->latest()
                ->take(5)
                ->get(),
            'modalExpenses' => Cashflow::where('mosque_id', $mosqueId)
                ->where('type', 'expenses')
                ->latest()
                ->take(5)
                ->get(),
            'modalCommittee' => Committee::where('mosque_id', $mosqueId)
                ->latest()
                ->take(5)
                ->get(),
            'modalInfo' => Info::where('mosque_id', $mosqueId)
                ->latest()
                ->take(5)
                ->get(),
            'modalItem' => Item::where('mosque_id', $mosqueId)
                ->latest()
                ->take(5)
                ->get(),
        ];
    });

    return view('home', array_merge(
        compact('title', 'currentMonthIncome', 'currentMonthExpenses',
                'incomePercentageChange', 'expensesPercentageChange',
                'totalAmount', 'totalAmountPercentageChange', 'timings'),
        $totals,
        $recentRecords
    ));
}

private function calculatePercentageChange($current, $previous)
{
    if ($previous > 0) {
        return (($current - $previous) / $previous) * 100;
    }
    return $current > 0 ? 100 : 0;
}

private function getPrayerTimes($mosque)
{
    try {
        $latitude = $mosque->latitude ?? 3.1390;
        $longitude = $mosque->longitude ?? 101.6869;
        $cacheKey = "prayer_times_{$mosque->id}_{$latitude}_{$longitude}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($latitude, $longitude) {
            $client = new Client();
            $response = $client->get("https://api.aladhan.com/v1/timings/" . now()->timestamp, [
                'query' => compact('latitude', 'longitude'),
                'timeout' => 10,
                'connect_timeout' => 5
            ]);

            $prayerTimes = json_decode($response->getBody()->getContents(), true);

            if ($prayerTimes['code'] == 200) {
                $timings = $prayerTimes['data']['timings'];
                $excludePrayers = ['Midnight', 'Firstthird', 'Lastthird'];
                return array_filter($timings, function ($key) use ($excludePrayers) {
                    return !in_array($key, $excludePrayers);
                }, ARRAY_FILTER_USE_KEY);
            }

            throw new \Exception('Invalid response from prayer times API');
        });
    } catch (\Exception $e) {
        Log::error('Error fetching prayer times', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return $this->getDefaultPrayerTimes();
    }
}
}
