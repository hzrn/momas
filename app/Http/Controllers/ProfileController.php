<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Profile::MosqueUser()->latest()->paginate(10);
        $title = 'Mosque Profile';
        return view('profile_index', compact('profile', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['profile'] = new Profile();
        $data['route'] = 'profile.store';
        $data['method'] = 'POST';
        $data['title'] = 'Add Mosque Profile';
        return view('profile_form', $data);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required',
            'content' => 'nullable|string',
            'url' => 'nullable'
        ]);

        Profile::create($requestData);
        flash('Data saved successfully')->success();

        return redirect()->route('profile.index')->with('success', 'Profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        $title = 'Mosque Profile Details'; // Title for the show view

        // Eager-load 'createdBy' and 'updatedBy' relationships
        $profile->load('createdBy', 'updatedBy');
        return view('profile_show', compact('profile', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        $data['profile'] = $profile;
        $data['route'] = ['profile.update', $profile->id];
        $data['method'] = 'PUT';
        $data['title'] = 'Edit Mosque Profile';
        return view('profile_form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required',
            'content' => 'nullable|string',
            'url' => 'nullable'
        ]);

        $profile->update($requestData + ['updated_by' => auth()->id()]);



        flash('Data edited successfully')->success();

        return redirect()->route('profile.index')->with('success', 'Profile edited successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();
        flash('Data deleted successfully')->success();
        return redirect()->route('profile.index')->with('success', 'Profile deleted successfully.');
    }
}
