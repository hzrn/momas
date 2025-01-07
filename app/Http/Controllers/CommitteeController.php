<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cache the committee list for 10 minutes
        $committee = Cache::remember('committee_list', 600, function () {
            Log::info('Committee list fetched from database and cached.');
            return Committee::MosqueUser()->orderBy('created_at', 'desc')->get();
        });

        Log::info('Committee list retrieved from cache.');
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

        Committee::create($requestData);
        Cache::forget('committee_list'); // Clear the cache after creating a new committee
        Log::info('New committee created and cache cleared.');
        flash(__('committee.saved'))->success();
        return redirect()->route('committee.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Committee $committee)
    {
        // Cache the specific committee for 10 minutes
        $cachedCommittee = Cache::remember("committee_{$committee->id}", 600, function () use ($committee) {
            Log::info("Committee ID {$committee->id} fetched from database and cached.");
            return $committee;
        });

        Log::info("Committee ID {$committee->id} retrieved from cache.");
        return view('committee_show', [
            'committee' => $cachedCommittee,
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
            // Delete the old photo if it exists
            $this->deletePhoto($committee->photo);

            // Upload the new photo
            $validatedData['photo'] = $this->storePhoto($request->file('photo'));
        } else {
            // Retain the existing photo if no new photo is uploaded
            $validatedData['photo'] = $committee->photo;
        }

        $committee->update($validatedData);
        Cache::forget('committee_list'); // Clear the cache after updating
        Cache::forget("committee_{$committee->id}"); // Clear the specific cache for the updated committee
        Log::info("Committee ID {$committee->id} updated and cache cleared.");
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
        Cache::forget('committee_list'); // Clear the cache after deletion
        Cache::forget("committee_{$committee->id}"); // Clear the specific cache for the deleted committee
        Log::info("Committee ID {$committee->id} deleted and cache cleared.");
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
