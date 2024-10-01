<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\CategoryInfo;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $info = Info::with('category')->MosqueUser()->latest()->paginate(10);
        $title = 'Info';
        return view('info_index', compact('info', 'title'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming you have a way to get the current mosque's ID, e.g., from the session or the authenticated user
        $currentMosqueId = auth()->user()->mosque_id; // Or another method to get the current mosque ID

        $data['info'] = new Info();
        $data['route'] = 'info.store';
        $data['method'] = 'POST';
        $data['categoryList'] = CategoryInfo::where('mosque_id', $currentMosqueId)->pluck('name', 'id'); // Filter by mosque
        $data['title'] = 'Add Info';

        return view('info_form', $data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category_info_id' => 'required|exists:category_infos,id',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'content' => 'nullable|string',
        ]);



        Info::create($requestData);
        flash('Data saved successfully')->success();

        return redirect()->route('info.index')->with('success', 'Info created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Info $info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Info $info)
    {
        $data['info'] = $info;
        $data['route'] = ['info.update', $info->id];
        $data['method'] = 'PUT';
        $data['categoryList'] = CategoryInfo::pluck('name', 'id');
        $data['title'] = 'Edit Info';
        return view('info_form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Info $info)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category_info_id' => 'required|exists:category_infos,id',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'content' => 'required|string',
        ]);

        $info->update($requestData);
        flash('Data edited successfully')->success();
        return redirect()->route('info.index')->with('success', 'Info updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Info $info)
{
    
    $mosque_id = $info->mosque_id;
    $info->delete();

    flash('Data deleted successfully')->success();

    return redirect()->route('info.index')->with('success', 'Data deleted successfully');
}
}
