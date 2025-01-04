<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Cache;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if the committee list is already cached; if not, retrieve and cache it
        $committee = Cache::tags(['committees'])->remember('committee_list', 60, function () {
            return Committee::MosqueUser()->orderBy('created_at', 'desc')->get();
        });

        return view('committee_index', [
            'committee' => $committee,
            'title' => __('committee.title'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('committee_form', [
            'committee' => new Committee(),
            'title' => __('committee.form_title'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_num' => 'required|string|max:15',
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($request->hasFile('photo')) {
            $imagePath = $this->storePhoto($request->file('photo'));
            $requestData['photo'] = $imagePath;
        }

        // Create the new committee
        Committee::create($requestData);

        // Clear the cached committee list since we added a new record
        Cache::tags(['committees'])->flush();

        flash(__('committee.saved'))->success();
        return redirect()->route('committee.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Committee $committee)
    {
        return view('committee_show', [
            'committee' => $committee,
            'title' => __('committee.details_title'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Committee $committee)
    {
        return view('committee_form', [
            'committee' => $committee,
            'title' => __('committee.edit_title'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Committee $committee)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_num' => 'required|string|max:15',
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($committee->photo); // Delete old photo from Cloudinary
            $validatedData['photo'] = $this->storePhoto($request->file('photo'));
        }

        $committee->update($validatedData);

        // Clear the cached committee list since we updated a record
        Cache::tags(['committees'])->flush();

        flash(__('committee.updated'))->success();
        return redirect()->route('committee.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Committee $committee)
    {
        $this->deletePhoto($committee->photo);
        $committee->delete();

        // Clear the cached committee list since we deleted a record
        Cache::tags(['committees'])->flush();

        flash(__('committee.deleted'))->success();
        return redirect()->route('committee.index');
    }

    /**
     * Export the committee list as a PDF.
     */
    public function exportPDF()
    {
        $committee = Committee::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ?? __('committee.no_mosque');

        $pdf = Pdf::loadView('committee_pdf', compact('committee', 'mosqueName'));
        return $pdf->download('committee_list.pdf');
    }

    /**
     * Store uploaded photo on Cloudinary and return the URL.
     */
    protected function storePhoto($image)
    {
        $result = Cloudinary::upload($image->getRealPath(), [
            'folder' => 'committees',
        ]);

        return $result->getSecurePath(); // Secure URL from Cloudinary
    }

    /**
     * Delete photo from Cloudinary if it exists.
     */
    protected function deletePhoto($photo)
    {
        if ($photo) {
            // Extract the public ID from the URL
            $publicId = basename(parse_url($photo, PHP_URL_PATH), '.' . pathinfo($photo, PATHINFO_EXTENSION));
            Cloudinary::destroy('committees/' . $publicId);
        }
    }
}
