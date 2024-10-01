<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashflow;
use App\Models\Mosque;
use Illuminate\Support\Facades\Storage;

class CashflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize the query with the MosqueUser scope
        $query = Cashflow::MosqueUser()->latest();

        // Check for date filters
        if ($request->has('start_date') && $request->has('end_date')) {
            // Add whereBetween clause to filter by date range
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Get all cashflow data without pagination to calculate totals
        $cashflows = $query->get();

        // Calculate totals
        $totalIncome = $cashflows->where('type', 'income')->sum('amount');
        $totalExpenses = $cashflows->where('type', 'expenses')->sum('amount');

        // Paginate data
        $cashflow = $query->paginate(10); // Example: 10 items per page

        $title = 'Cashflow';

        // Pass data to view
        return view('cashflow_index', compact('cashflow', 'totalIncome', 'totalExpenses', 'title'));
    }

    public function create()
    {
        $cashflow = new Cashflow();
        $title = 'Cashflow Form';
        return view('cashflow_form', compact('cashflow', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'date' => 'required|date',
            'category' => 'nullable|string',
            'description' => 'required|string',
            'type' => 'required|in:income,expenses',
            'amount' => 'required|numeric|min:0.01',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/cashflows', $imageName);

            // Update request data with image name
            $requestData['photo'] = $imageName;
        }

        $cashflow = Cashflow::create($requestData); // Use create method for better readability

        $this->updateTotalAmount($cashflow->mosque_id);

        flash('Data saved successfully')->success();

        return redirect()->route('cashflow.index')->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cashflow $cashflow)
    {
        $title = 'Cashflow Details';
        return view('cashflow_show', compact('cashflow', 'title'));
    }

    public function edit(Cashflow $cashflow)
    {
        $title = 'Cashflow Edit';
        return view('cashflow_form',compact('cashflow', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cashflow $cashflow)
    {


        $validatedData = $request->validate([
            'date' => 'required|date',
            'category' => 'nullable|string',
            'description' => 'required|string',
            'type' => 'required|in:income,expenses',
            'amount' => 'required|numeric|min:0.01',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check for a new photo upload
        if ($request->hasFile('photo')) {
            // Remove the old photo if it exists
            if ($cashflow->photo) {
                Storage::delete('public/cashflows/' . $cashflow->photo);
            }

            $image = $request->file('photo');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/cashflows', $imageName);

            // Update request data with new image name
            $validatedData['photo'] = $imageName;
        }

        $cashflow->update($validatedData + ['updated_by' => auth()->id()]);

        $this->updateTotalAmount($cashflow->mosque_id);

        flash('Data updated successfully')->success();

        return redirect()->route('cashflow.index')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cashflow $cashflow)
    {

        $mosque_id = $cashflow->mosque_id;

        // Delete the photo if it exists
        if ($cashflow->photo) {
            Storage::delete('public/cashflows/' . $cashflow->photo);
        }

        $cashflow->delete();

        $this->updateTotalAmount($mosque_id);

        flash('Data deleted successfully')->success();

        return redirect()->route('cashflow.index')->with('success', 'Data deleted successfully');
    }

    private function updateTotalAmount($mosque_id)
    {
        $mosque = Mosque::findOrFail($mosque_id);

        $totalIncome = $mosque->cashflows()->where('type', 'income')->sum('amount');
        $totalExpenses = $mosque->cashflows()->where('type', 'expenses')->sum('amount');

        $mosque->total_amount = $totalIncome - $totalExpenses;
        $mosque->save();
    }
}
