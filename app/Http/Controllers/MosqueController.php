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
        return view('mosque_form', [
            'mosque' => $mosque,
            'title' => __('mosque.form_title'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_num' => 'required|numeric', // Ensure phone number is numeric
            'email' => 'required|email|max:255', // Ensure valid email format
        ]);

        // Retrieve the authenticated user's mosque or create a new instance
        $mosque = auth()->user()->mosque ?? new Mosque();

        // Assign validated data to the mosque
        $mosque->name = $data['name'];
        $mosque->address = $data['address'];
        $mosque->phone_num = $data['phone_num'];
        $mosque->email = $data['email'];
        $mosque->save();

        // Associate the mosque with the user
        $user = auth()->user();
        $user->mosque_id = $mosque->id;
        $user->save();

        // Flash success message
        flash(__('mosque.saved'))->success();

        // Redirect back to the form with success message
        return redirect()->route('mosque.create');
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
