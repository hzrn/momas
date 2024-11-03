<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Profile::MosqueUser()->latest()->paginate(10);
        return view('profile_index', ['profile' => $profile, 'title' => __('profile.title')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profile_form', [
            'profile' => new Profile(),
            'route' => 'profile.store',
            'method' => 'POST',
            'title' => __('profile.form_title')
        ]);
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

        flash(__('profile.saved'))->success();
        return redirect()->route('profile.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        $profile->load('createdBy', 'updatedBy');  // Eager-load relationships
        return view('profile_show', ['profile' => $profile, 'title' => __('profile.details_title')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        return view('profile_form', [
            'profile' => $profile,
            'route' => ['profile.update', $profile->id],
            'method' => 'PUT',
            'title' => __('profile.edit_title')
        ]);
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

        flash(__('profile.updated'))->success();
        return redirect()->route('profile.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();

        flash(__('profile.deleted'))->success();
        return redirect()->route('profile.index');
    }

    /**
     * Export the profile list as a PDF.
     */
    public function exportPDF()
    {
        $profile = Profile::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ?? 'No mosque assigned';

        $pdf = Pdf::loadView('profile_pdf', compact('profile', 'mosqueName'));
        return $pdf->download('profile_list.pdf');
    }
}
