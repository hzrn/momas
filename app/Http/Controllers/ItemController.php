<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\CategoryItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        $items = Item::with('category')->MosqueUser()->latest()->paginate(10);
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
}
