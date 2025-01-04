<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashflow;
use App\Models\Mosque;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CashflowController extends Controller
{
    public function index(Request $request)
    {
        $query = Cashflow::MosqueUser();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            // If only start_date is provided, filter from that date onward
            $query->where('date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            // If only end_date is provided, filter up to that date
            $query->where('date', '<=', $request->end_date);
        }


        $cashflows = $query->get();
        $totalIncome = $cashflows->where('type', 'income')->sum('amount');
        $totalExpenses = $cashflows->where('type', 'expenses')->sum('amount');

        $cashflow = $query->orderBy('created_at', 'desc')->get();
        $title = __('cashflow.title');

        return view('cashflow_index', compact('cashflow', 'totalIncome', 'totalExpenses', 'title'));
    }

    public function create()
    {
        return view('cashflow_form', ['cashflow' => new Cashflow(), 'title' => __('cashflow.form_title')]);
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expenses',
            'amount' => 'required|numeric|min:0.01',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('photo')) {
            $imagePath = $this->storePhoto($request->file('photo'));
            $requestData['photo'] = $imagePath;
        }

        $cashflow = Cashflow::create($requestData);
        // $this->updateTotalAmount($cashflow->mosque_id);

        flash(__('cashflow.saved'))->success();
        return redirect()->route('cashflow.index');
    }

    public function show(Cashflow $cashflow)
    {
        return view('cashflow_show', ['cashflow' => $cashflow, 'title' => __('cashflow.details_title'),]);
    }

    public function edit(Cashflow $cashflow)
    {
        return view('cashflow_form', ['cashflow' => $cashflow, 'title' => __('cashflow.edit_title'),]);
    }

    public function update(Request $request, Cashflow $cashflow)
    {
        $requestData = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expenses',
            'amount' => 'required|numeric|min:0.01',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($cashflow->photo); // Delete old photo from Cloudinary
            $requestData['photo'] = $this->storePhoto($request->file('photo'));
        }

        $cashflow->update($requestData + ['updated_by' => auth()->id()]);
        // $this->updateTotalAmount($cashflow->mosque_id);

        flash(__('cashflow.updated'))->success();
        return redirect()->route('cashflow.index');
    }

    public function destroy(Cashflow $cashflow)
    {
        $this->deletePhoto($cashflow->photo); // Delete photo from Cloudinary
        $cashflow->delete();

        flash(__('cashflow.deleted'))->success();
        return redirect()->route('cashflow.index');
    }

        /**
     * Store uploaded photo on Cloudinary and return the URL.
     */
    protected function storePhoto($image)
    {
        $result = Cloudinary::upload($image->getRealPath(), [
            'folder' => 'cashflows',
        ]);

        return $result->getSecurePath(); // Secure URL from Cloudinary
    }

    /**
     * Delete photo from Cloudinary if it exists.
     */
    protected function deletePhoto($photo)
    {
        if ($photo) {
            // Extract the public ID from the URL
            $publicId = basename(parse_url($photo, PHP_URL_PATH), '.' . pathinfo($photo, PATHINFO_EXTENSION));
            Cloudinary::destroy('cashflows/' . $publicId);
        }
    }

    // private function updateTotalAmount($mosque_id)
    // {
    //     $mosque = Mosque::findOrFail($mosque_id);
    //     $mosque->total_amount = $mosque->cashflows()->where('type', 'income')->sum('amount') -
    //         $mosque->cashflows()->where('type', 'expenses')->sum('amount');
    //     $mosque->save();
    // }

    public function exportPDF()
    {
        $cashflow = Cashflow::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ??  __('cashflow.no_mosque');

        $pdf = Pdf::loadView('cashflow_pdf', compact('cashflow', 'mosqueName'));
        return $pdf->download('cashflow_list.pdf');
    }

    public function cashflowAnalysis(Request $request)
    {
        $title = __('cashflow.analysis_title');
        // Get the selected year and month from the request
        $selectedYear = $request->input('year', now()->year);
        $selectedMonth = $request->input('month', null);

        // Prepare query for monthly cashflow data
        $query = Cashflow::selectRaw('
            DATE_FORMAT(date, "%Y-%m") as month,
            SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = "expenses" THEN amount ELSE 0 END) as total_expenses
        ')
        ->where('mosque_id', auth()->user()->mosque_id)
        ->whereYear('date', $selectedYear)
        ->groupBy('month')
        ->orderBy('month');

        // Apply month filter if provided
        if ($selectedMonth) {
            $query->whereMonth('date', $selectedMonth);
        }

        // Execute the query
        $monthlyData = $query->get();

        // Prepare monthly cashflow data
        $monthlyChartData = [];
        $totalIncome = 0;
        $totalExpenses = 0;

        // Ensure all months are represented
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthName = date('F', mktime(0, 0, 0, $i, 1));

            $monthData = $monthlyData->firstWhere('month', date('Y-m', strtotime("$selectedYear-$monthKey-01")));

            $income = $monthData ? floatval($monthData->total_income) : 0;
            $expenses = $monthData ? floatval($monthData->total_expenses) : 0;

            $monthlyChartData[$monthName] = [
                'income' => $income,
                'expenses' => $expenses
            ];

            $totalIncome += $income;
            $totalExpenses += $expenses;
        }

        // Prepare response data
        $responseData = [
            'chartData' => $monthlyChartData,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'year' => $selectedYear,
            'month' => $selectedMonth,

        ];

        // Return JSON response for AJAX requests or view for regular requests
        return $request->ajax()
            ? response()->json($responseData)
            : view('cashflow_analysis', [
                'monthlyData' => $responseData,
                'selectedYear' => $selectedYear,
                'selectedMonth' => $selectedMonth,
                'title' => $title
            ]);
    }

    public function getLineChart(Request $request)
    {
        try {
            $selectedYear = $request->input('year', now()->year);

            $query = Cashflow::selectRaw('
                DATE_FORMAT(date, "%Y-%m") as month,
                SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "expenses" THEN amount ELSE 0 END) as total_expenses
            ')
            ->where('mosque_id', auth()->user()->mosque_id)
            ->whereYear('date', $selectedYear)
            ->groupBy('month')
            ->orderBy('month');

            $monthlyData = $query->get();

            $monthlyChartData = [];
            $totalIncome = 0;
            $totalExpenses = 0;

            $monthNames = getMonthNames();

            foreach ($monthNames as $monthNum => $monthName) {
                $monthData = $monthlyData->first(function ($item) use ($selectedYear, $monthNum) {
                    return $item->month === "{$selectedYear}-{$monthNum}";
                });

                $income = $monthData ? floatval($monthData->total_income) : 0;
                $expenses = $monthData ? floatval($monthData->total_expenses) : 0;

                $monthlyChartData[$monthName] = [
                    'income' => $income,
                    'expenses' => $expenses
                ];

                $totalIncome += $income;
                $totalExpenses += $expenses;
            }

            $responseData = [
                'chartData' => $monthlyChartData,
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'year' => $selectedYear,
                'monthNames' => $monthNames,
            ];

            return response()->json($responseData);

        } catch (\Exception $e) {
            \Log::error('Line Chart Data Fetch Error: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'An error occurred while fetching data',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function getDailyCashflow(Request $request)
    {
        try {
            $selectedYear = $request->input('year', now()->year);
            $selectedMonth = $request->input('month', now()->format('m'));

            // Prepare query for daily cashflow data
            $dailyData = Cashflow::selectRaw('
                    DATE_FORMAT(date, "%Y-%m-%d") as day,
                    SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = "expenses" THEN amount ELSE 0 END) as total_expenses
                ')
                ->where('mosque_id', auth()->user()->mosque_id)
                ->whereYear('date', $selectedYear)
                ->whereMonth('date', $selectedMonth)
                ->groupBy('day')
                ->orderBy('day') // Ensure the dates are ordered
                ->get();

            // Prepare daily cashflow data
            $dailyChartData = [];
            $totalIncome = 0;
            $totalExpenses = 0;

            // Combine income and expenses into one dataset
            foreach ($dailyData as $data) {
                $date = $data->day;
                $combinedTotal = floatval($data->total_income) - floatval($data->total_expenses); // Use income - expenses for the combined value

                $dailyChartData[$date] = $combinedTotal; // Store combined total by date
                $totalIncome += floatval($data->total_income);
                $totalExpenses += floatval($data->total_expenses);
            }

            // Prepare response data
            $responseData = [
                'chartData' => $dailyChartData,
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'year' => $selectedYear,
                'month' => $selectedMonth
            ];

            return response()->json($responseData);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching daily cashflow: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json([
                'error' => 'An error occurred while fetching daily cashflow data.',
                'message' => $e->getMessage() // Optionally include the error message for debugging
            ], 500); // HTTP status code 500 for internal server error
        }
    }

    public function getPieChart(Request $request)
    {
        try {
            $selectedYear = $request->input('year', now()->year);
            $selectedMonth = $request->input('month');

            // Base query to get total income and total expenses
            $query = Cashflow::selectRaw('
                SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "expenses" THEN amount ELSE 0 END) as total_expenses
            ')
            ->where('mosque_id', auth()->user()->mosque_id);

            // Filter by month if provided
            if ($selectedMonth) {
                $query->whereMonth('date', $selectedMonth);
            }

            // Filter by year
            $query->whereYear('date', $selectedYear);

            // Execute the query
            $yearlyData = $query->first();

            // Define a list of all possible categories for both income and expenses
            $incomeCategoriesList = [
                'Sadaqah', 'Zakat', 'Waqf', 'Fitrah', 'Donation', 'General'
            ];

            $expenseCategoriesList = [
                'Utilities', 'Maintenance', 'Salaries', 'Supplies', 'Events', 'Insurance', 'Miscellaneous'
            ];

            // Query breakdown by category, even if category has no transactions (e.g., RM 0)
            $categories = Cashflow::select('category', 'type', \DB::raw('SUM(amount) as total'))
                ->where('mosque_id', auth()->user()->mosque_id)
                ->whereYear('date', $selectedYear)
                ->when($selectedMonth, function ($query) use ($selectedMonth) {
                    return $query->whereMonth('date', $selectedMonth);
                })
                ->groupBy('category', 'type')
                ->get()
                ->groupBy('type');

            // Split categories into income and expenses
            $incomeCategories = $categories->get('income', collect());
            $expenseCategories = $categories->get('expenses', collect());

            // Ensure all income and expense categories exist even if they have RM0
            $incomeCategories = $this->ensureCategoriesExist($incomeCategoriesList, $incomeCategories, 'income');
            $expenseCategories = $this->ensureCategoriesExist($expenseCategoriesList, $expenseCategories, 'expenses');

            // Prepare data for pie chart
            $data = [
                'labels' => [__('cashflow.income'), __('cashflow.expenses')],
                'values' => [
                    floatval($yearlyData->total_income),
                    floatval($yearlyData->total_expenses)
                ],
                'year' => $selectedYear,
                'totalIncome' => floatval($yearlyData->total_income),
                'totalExpenses' => floatval($yearlyData->total_expenses),
                'categories' => [
                    'income' => $incomeCategories,
                    'expenses' => $expenseCategories
                ]
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching pie chart data: ' . $e->getMessage());

            // Return a user-friendly error message
            return response()->json([
                'error' => 'An error occurred while fetching pie chart data. Please try again later.'
            ], 500);
        }
    }

    // Helper function to ensure categories exist even if they don't have data (default to 0)
    private function ensureCategoriesExist($categoryList, $categoryData, $type)
    {
        // Create a collection with the categories having 0 as total
        $categoryCollection = collect();

        foreach ($categoryList as $category) {
            // Check if the category exists in the current category data
            $existingCategory = $categoryData->firstWhere('category', $category);

            if ($existingCategory) {
                // If category exists, push it to the result
                $categoryCollection->push($existingCategory);
            } else {
                // If category doesn't exist, create it with a total of 0
                $categoryCollection->push([
                    'category' => $category,
                    'type' => $type,
                    'total' => 0
                ]);
            }
        }

        return $categoryCollection;
    }

}
