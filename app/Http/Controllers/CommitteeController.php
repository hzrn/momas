<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $committee = Committee::MosqueUser()->latest()->paginate(10);
        $title = 'Committee';
        return view('committee_index', compact('committee', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $committee = new Committee();
        $title = 'Committee Form';
        return view('committee_form', compact('committee', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $requestData = $request->validate([
        'name' => 'required|string|max:255',
        'phone_num' => 'required|string|min:0|max:15',
        'position' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',

    ]);

    // Handle image upload
    if ($request->hasFile('photo')) {

        $image = $request->file('photo');
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/committees', $imageName);

        // Update request data with image name
        $requestData['photo'] = $imageName;
    }



    // Create a new committee record with validated data
    $committee = Committee::create($requestData);

    flash('Data saved successfully')->success();
    return redirect()->route('committee.index')->with('success', 'Data saved successfully');
}


    /**
     * Display the specified resource.
     */
    public function show(Committee $committee)
    {
        $title = 'Committee Details';
        return view('committee_show', compact('committee', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Committee $committee)
    {
        $data['committee'] = $committee;
        $data['route'] = ['committee.update', $committee->id];
        $data['method'] = 'PUT';
        $data['title'] = 'Edit Committee';
        return view('committee_form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Committee $committee)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'phone_num' => 'required|string|min:0|max:15',
        'position' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',

    ]);

    // Handle image upload
    if ($request->hasFile('photo')) {
        // Remove the old photo if it exists
        if ($committee->photo) {
            Storage::delete('public/committees/' . $committee->photo);
        }

        $image = $request->file('photo');
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public/committees', $imageName);

        // Update request data with new image name
        $validatedData['photo'] = $imageName;
    }



    // Update the committee record
    $committee->update($validatedData + ['updated_by' => auth()->id()]);

    flash('Data updated successfully')->success();
    return redirect()->route('committee.index')->with('success', 'Data updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Committee $committee)
    {
        if ($committee->photo) {
            Storage::delete('public/committees/' . $committee->photo);
        }

        $committee->delete();
        flash('Data deleted successfully')->success();
        return redirect()->route('committee.index')->with('success', 'Data deleted successfully');
    }
}
