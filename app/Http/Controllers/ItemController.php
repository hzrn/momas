<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\CategoryItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        $items = Item::with('category')->MosqueUser()->orderBy('created_at', 'desc')->get();;
        return view('item_index', compact('items'))->with('title', __('item.title'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categoryList = CategoryItem::where('mosque_id', auth()->user()->mosque_id)->pluck('name', 'id');
        return view('item_form', [
            'item' => new Item(),
            'route' => 'item.store',
            'method' => 'POST',
            'categoryList' => $categoryList,
            'title' => __('item.form_title'),
        ]);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'name' => 'required|string|max:255',
            'category_item_id' => 'required|exists:category_items,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $imageName = uniqid() . '.' . $request->photo->extension();
            $request->photo->storeAs('public/items', $imageName);
            $requestData['photo'] = $imageName;
        }

        $requestData['created_by'] = auth()->id();
        Item::create($requestData);
        flash(__('item.saved'))->success();
        return redirect()->route('item.index');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        $item->load('createdBy', 'updatedBy');
        return view('item_show', compact('item'))->with('title', __('item.details_title'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $categoryList = CategoryItem::pluck('name', 'id');
        return view('item_form', [
            'item' => $item,
            'route' => ['item.update', $item->id],
            'method' => 'PUT',
            'categoryList' => $categoryList,
            'title' => __('item.edit_title'),
        ]);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, Item $item)
    {
        $requestData = $request->validate([
            'name' => 'required|string|max:255',
            'category_item_id' => 'required|exists:category_items,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::delete('public/items/' . $item->photo);
            }
            $imageName = uniqid() . '.' . $request->photo->extension();
            $request->photo->storeAs('public/items', $imageName);
            $requestData['photo'] = $imageName;
        }

        $item->update($requestData + ['updated_by' => auth()->id()]);
        flash(__('item.updated'))->success();
        return redirect()->route('item.index');
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Item $item)
    {
        if ($item->photo) {
            Storage::delete('public/items/' . $item->photo);
        }
        $item->delete();

        flash(__('item.deleted'))->success();
        return redirect()->route('item.index');
    }

    /**
     * Export the item list as a PDF.
     */
    public function exportPDF()
    {
        $items = Item::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ?? __('item.no_mosque');

        $pdf = Pdf::loadView('item_pdf', compact('items', 'mosqueName'));
        return $pdf->download('item_list.pdf');
    }

    public function itemAnalysis()
    {
        return view('item_analysis', ['title' => __('item.analysis_title')]);
    }

    public function lineChart(Request $request)
    {
        // Get the year from the request, default to the current year
        $year = $request->input('year', now()->year);

        // Validate the year (optional but recommended)
        if (!is_numeric($year) || $year < 2000 || $year > now()->year) {
            Log::warning('Invalid year provided:', ['year' => $year]);
            return response()->json(['error' => 'Invalid year provided.'], 400);
        }

        // Query to count the number of rows (count of IDs) grouped by month
        $lineData = Item::select(
            \DB::raw('MONTH(created_at) as month'), // Use numeric month for ordering
            \DB::raw('SUM(quantity) as total') // Sum quantities instead of counting IDs
        )
            ->where('mosque_id', auth()->user()->mosque_id)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month'); // Retrieve totals indexed by month number

        // Define all months using the helper function
        $monthNames = collect(array_values(getMonthNames()));

        // Map the totals to month names, defaulting to 0 if no data
        $totals = collect(range(1, 12))->map(function ($month) use ($lineData) {
            return $lineData[$month] ?? 0; // Use numeric month for lookup
        })->toArray(); // Convert the Collection to an array

        // Return the month names and totals as JSON
        return response()->json([
            'months' => $monthNames, // Return translated month names
            'totals' => array_map('intval', $totals), // Ensure totals are integers
        ]);
    }

    public function fetchPieChartData(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', null); // Optional month filter

        // Query items filtered by year and optionally by month
        $itemQuery = Item::where('mosque_id', auth()->user()->mosque_id)
            ->whereYear('created_at', $year);

        if ($month) {
            $itemQuery->whereMonth('created_at', $month);
        }

        // Get the quantities grouped by category
        $categoryQuantities = $itemQuery->select(
            'category_item_id',
            \DB::raw('SUM(quantity) as total_quantity')
        )
            ->groupBy('category_item_id')
            ->get();

        $labels = [];
        $values = [];

        foreach ($categoryQuantities as $entry) {
            $category = CategoryItem::find($entry->category_item_id);
            if ($category) {
                $labels[] = $category->name;
                $values[] = $entry->total_quantity; // Use total quantity for each category
            }
        }

        $monthNames = getMonthNames();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'totalEntries' => array_sum($values), // Total quantity of all items
            'month' => $month ? ($monthNames[str_pad($month, 2, '0', STR_PAD_LEFT)] ?? __('cashflow.all_months')) : __('cashflow.all_months'),
        ]);
    }

}
