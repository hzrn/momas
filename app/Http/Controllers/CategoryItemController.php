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
        $categoryitem = cache()->remember('category_items', 60, function () {
            return CategoryItem::MosqueUser()
                ->orderBy('created_at', 'desc')
                ->get();
        });

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

        CategoryItem::create($requestData);

        // Clear cache
        cache()->forget('category_items');

        flash(__('categoryitem.saved'))->success();
        return redirect()->route('categoryitem.index');
    }

    public function update(Request $request, $id)
    {
        $categoryitem = CategoryItem::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $categoryitem->update($validatedData);

        // Clear cache
        cache()->forget('category_items');

        flash(__('categoryitem.updated'))->success();
        return redirect()->route('categoryitem.index');
    }

    public function destroy($id)
    {
        $categoryitem = CategoryItem::findOrFail($id);
        $categoryitem->delete();

        // Clear cache
        cache()->forget('category_items');

        flash(__('categoryitem.deleted'))->success();
        return redirect()->route('categoryitem.index');
    }

}
