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

        // Retrieve total income and expenses for the specific mosque
        $mosqueId = auth()->user()->mosque_id; // Adjust this to get the correct mosque id
        $mosque = Mosque::find($mosqueId); // Get the mosque from the database to retrieve latitude and longitude

        // If mosque does not exist, return with error or handle accordingly
        if (!$mosque) {
            return redirect()->route('home')->withErrors(['error' => 'Mosque not found']);
        }

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

        try {
            // Default coordinates for Kuala Lumpur
            $latitude = $mosque->latitude ?? 3.1390;
            $longitude = $mosque->longitude ?? 101.6869;

            // Create a unique cache key based on mosque ID and coordinates
            $cacheKey = "prayer_times_{$mosqueId}_{$latitude}_{$longitude}";

            // Check if cached times exist and are recent (cache for 24 hours)
            $cachedTimes = Cache::get($cacheKey);
            if ($cachedTimes) {
                $timings = $cachedTimes;
            } else {
                // Get current timestamp for prayer time request
                $timestamp = now()->timestamp;

                // Initialize Guzzle client and make the API request to Aladhan API
                $client = new Client();

                // Add timeout and connect timeout to prevent hanging
                $response = $client->get("https://api.aladhan.com/v1/timings/{$timestamp}", [
                    'query' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ],
                    'timeout' => 10,         // Total timeout of 10 seconds
                    'connect_timeout' => 5   // Connection timeout of 5 seconds
                ]);

                // Decode the JSON response
                $prayerTimes = json_decode($response->getBody()->getContents(), true);

                // Check if the API response was successful
                if ($prayerTimes['code'] == 200) {
                    $timings = $prayerTimes['data']['timings'];

                    // Exclude the specified prayer times: Midnight, First third, and Last third
                    $excludePrayers = ['Midnight', 'Firstthird', 'Lastthird'];

                    // Filter out the unwanted prayer times
                    $timings = array_filter($timings, function ($key) use ($excludePrayers) {
                        return !in_array($key, $excludePrayers);
                    }, ARRAY_FILTER_USE_KEY);

                    // Cache the successful prayer times for 24 hours
                    Cache::put($cacheKey, $timings, now()->addHours(24));
                } else {
                    // Log the error
                    Log::warning('Aladhan API returned non-200 status', [
                        'response' => $prayerTimes
                    ]);

                    // Fallback to default prayer times
                    $timings = $this->getDefaultPrayerTimes();
                }
            }
        } catch (\Exception $e) {
            // Log the full exception details
            Log::error('Error fetching prayer times', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback to default prayer times
            $timings = $this->getDefaultPrayerTimes();
        }

        // Retrieve totals filtered by mosque_id
        $totalCommittees = Committee::where('mosque_id', $mosqueId)->count();
        $totalInfo = Info::where('mosque_id', $mosqueId)->count(); // Assuming `Info` has `mosque_id`
        $totalItems = Item::where('mosque_id', $mosqueId)->count(); // Assuming `Item` has `mosque_id`

        $modalIncome = Cashflow::where('mosque_id', $mosqueId)
        ->where('type', 'income') // Apply 'type' filter before executing the query
        ->latest()
        ->take(5)
        ->get();

        $modalExpenses = Cashflow::where('mosque_id', $mosqueId)
        ->where('type', 'expenses') // Apply 'type' filter before executing the query
        ->latest()
        ->take(5)
        ->get();

        $modalCommittee = Committee::where('mosque_id', $mosqueId)
        ->latest()
        ->take(5)
        ->get();

        $modalInfo = Info::where('mosque_id', $mosqueId)
        ->latest()
        ->take(5)
        ->get();

        $modalItem = Item::where('mosque_id', $mosqueId)
        ->latest()
        ->take(5)
        ->get();


        // Pass data to view
        return view('home', compact(
            'currentMonthIncome',
            'currentMonthExpenses',
            'incomePercentageChange',
            'expensesPercentageChange',
            'totalAmount',
            'totalAmountPercentageChange',
            'timings',
            'totalCommittees',
            'totalInfo',
            'totalItems',
            'title',
            'modalIncome',
            'modalExpenses',
            'modalCommittee',
            'modalInfo',
            'modalItem',
        ));

    }
}
