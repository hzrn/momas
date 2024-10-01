<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\User;
use App\Http\Requests\StoreMosqueRequest;
use App\Http\Requests\UpdateMosqueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MosqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $mosque = auth()->user()->mosque;
        $mosque = $mosque ?? new Mosque();
        return view('mosque_form',[
            'mosque' => $mosque,
            'title' => 'Mosque Form',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_num' => 'required|numeric',  // You can adjust min/max validation rules as needed
            'email' => 'required|email|max:255',
        ]);

        // Retrieve the current authenticated user
        $user = auth()->user();

        // Use updateOrCreate to handle the mosque creation or update in a single step
        $mosque = Mosque::updateOrCreate(
            ['id' => $user->mosque_id], // If user already has a mosque, update it
            $data                         // Use validated data to update or create a mosque
        );

        // Update the user's mosque_id if it's a new mosque
        if (!$user->mosque_id) {
            $user->update(['mosque_id' => $mosque->id]);
        }

        flash('Data saved successfully')->success();
        return back();
    }


    /**
     * Display the specified resource.
     */
    public function show(Mosque $mosque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mosque $mosque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMosqueRequest $request, Mosque $mosque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mosque $mosque)
    {
        //
    }
}
