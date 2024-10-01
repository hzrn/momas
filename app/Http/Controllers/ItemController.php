<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\CategoryItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $item = Item::with('category')->MosqueUser()->latest()->paginate(10);
        $title = 'Item';
        return view('item_index', compact('item', 'title'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming you have a way to get the current mosque's ID, e.g., from the session or the authenticated user
        $currentMosqueId = auth()->user()->mosque_id; // Or another method to get the current mosque ID
    
        $data['item'] = new Item();
        $data['route'] = 'item.store';
        $data['method'] = 'POST';
        $data['categoryList'] = CategoryItem::where('mosque_id', $currentMosqueId)->pluck('name', 'id'); // Filter by mosque
        $data['title'] = 'Add Item';
    
        return view('item_form', $data);
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $requestData = $request->validate([
            'name' => 'required|string|max:255', // Name of the item
            'category_item_id' => 'required|exists:category_items,id', // Valid category ID
            'description' => 'nullable|string', // Optional description
            'quantity' => 'required|integer|min:1', // Quantity should be a positive integer
            'price' => 'required|numeric|min:0', // Price should be a positive decimal number
        ]);
    
        // Add the created_by field to the request data, assuming the user is authenticated
        $requestData['created_by'] = auth()->user()->id;
    
        // Create the item with the validated data
        Item::create($requestData);
    
        // Flash success message and redirect to the item index page
        flash('Item created successfully')->success();
    
        return redirect()->route('item.index')->with('success', 'Item created successfully');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $data['item'] = $item;
        $data['route'] = ['item.update', $item->id];
        $data['method'] = 'PUT';
        $data['categoryList'] = CategoryItem::pluck('name', 'id');
        $data['title'] = 'Edit Item';
        return view('item_form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $requestData = $request->validate([
            'name' => 'required|string|max:255', // Name of the item
            'category_item_id' => 'required|exists:category_items,id', // Valid category ID
            'description' => 'nullable|string', // Optional description
            'quantity' => 'required|integer|min:1', // Quantity should be a positive integer
            'price' => 'required|numeric|min:0', // Price should be a positive decimal number
        ]);

        $item->update($requestData);
        flash('Data edited successfully')->success();

        return redirect()->route('item.index')->with('success', 'Item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $item = Item::findOrFail($id);
    $mosque_id = $item->mosque_id;
    $item->delete();

    flash('Data deleted successfully')->success();

    return redirect()->route('item.index')->with('success', 'Data deleted successfully');
}
}
