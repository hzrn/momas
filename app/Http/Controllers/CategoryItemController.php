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
        $categoryitem = CategoryItem::MosqueUser()->orderBy('created_at', 'desc')->get();;
        $title = __('categoryitem.title');
        return view('categoryitem_index', compact('categoryitem', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryitem = new CategoryItem();
        $title = __('categoryitem.form_title');
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
        flash(__('categoryitem.saved'))->success();
        return redirect()->route('categoryitem.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $title = __('categoryitem.title');
        return view('categoryitem_form', compact('categoryitem', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $title = __('categoryitem.edit_title');
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
        flash(__('categoryitem.updated'))->success();
        return redirect()->route('categoryitem.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $categoryitem->delete();
        flash(__('categoryitem.deleted'))->success();
        return redirect()->route('categoryitem.index');
    }
}
