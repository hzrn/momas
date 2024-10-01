<?php

namespace App\Http\Controllers;

use App\Models\CategoryItem;
use Illuminate\Http\Request;

class CategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryitem = CategoryItem::MosqueUser()->latest()->paginate(10);
        $title = 'Category Item';
        return view('categoryitem_index', compact('categoryitem', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryitem = new CategoryItem();
        $title = 'Category Item Form';
        return view('categoryitem_form', compact('categoryitem', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'name' => 'required',
            
        ]);

        $categoryitem = CategoryItem::create($requestData);
        flash('Data saved successfully')->success();
        return redirect()->route('categoryitem.index')->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $title = 'Category Item';
        return view('categoryitem_form', compact('categoryitem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $title = 'Category Item Edit';
        return view('categoryitem_form', compact('categoryitem', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoryitem = CategoryItem::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            
        ]);

        $categoryitem->update($validatedData);

        flash('Data updated successfully')->success();

        return redirect()->route('categoryitem.index')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $categoryitem->delete();

        flash('Data deleted successfully')->success();

        return redirect()->route('categoryitem.index')->with('success', 'Data deleted successfully');
    }
}
