<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashflow;
use App\Models\Mosque;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CashflowController extends Controller
{
    public function index(Request $request)
    {
        $query = Cashflow::MosqueUser()->latest();

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

        $cashflow = $query->paginate(10);
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
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expenses',
            'amount' => 'required|numeric|min:0.01',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/cashflows', $imageName);
            $requestData['photo'] = $imageName;
        }

        $cashflow = Cashflow::create($requestData);
        $this->updateTotalAmount($cashflow->mosque_id);

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
        $validatedData = $request->validate([
            'date' => 'required|date',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expenses',
            'amount' => 'required|numeric|min:0.01',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($cashflow->photo) {
                Storage::delete('public/cashflows/' . $cashflow->photo);
            }

            $image = $request->file('photo');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/cashflows', $imageName);
            $validatedData['photo'] = $imageName;
        }

        $cashflow->update($validatedData + ['updated_by' => auth()->id()]);
        $this->updateTotalAmount($cashflow->mosque_id);

        flash(__('cashflow.updated'))->success();
        return redirect()->route('cashflow.index');
    }

    public function destroy(Cashflow $cashflow)
    {
        if ($cashflow->photo) {
            Storage::delete('public/cashflows/' . $cashflow->photo);
        }

        $cashflow->delete();
        $this->updateTotalAmount($cashflow->mosque_id);

        flash(__('cashflow.deleted'))->success();
        return redirect()->route('cashflow.index');
    }

    private function updateTotalAmount($mosque_id)
    {
        $mosque = Mosque::findOrFail($mosque_id);
        $mosque->total_amount = $mosque->cashflows()->where('type', 'income')->sum('amount') -
            $mosque->cashflows()->where('type', 'expenses')->sum('amount');
        $mosque->save();
    }

    public function exportPDF()
    {
        $cashflow = Cashflow::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ??  __('cashflow.no_mosque');

        $pdf = Pdf::loadView('cashflow_pdf', compact('cashflow', 'mosqueName'));
        return $pdf->download('cashflow_list.pdf');
    }
}
