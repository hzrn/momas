<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\Log;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve committees ordered by latest created first
        $committee = Committee::MosqueUser()->orderBy('created_at', 'desc')->get();
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
        try {
            $requestData = $request->validate([
                'name' => 'required|string|max:255',
                'phone_num' => 'required|string|max:15',
                'position' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($request->hasFile('photo')) {
                $imagePath = $this->storePhoto($request->file('photo'));
                $requestData['photo'] = $imagePath;
            }

            Committee::create($requestData);
            flash(__('committee.saved'))->success();
            return redirect()->route('committee.index');
        } catch (PostTooLargeException $e) {
            return back()->withErrors(['photo' => __('The uploaded file is too large. Please upload a smaller image.')])
                         ->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('An unexpected error occurred. Please try again.')])
                         ->withInput();
        }
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
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone_num' => 'required|string|max:15',
                'position' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                $this->deletePhoto($committee->photo);

                // Upload the new photo and store its URL
                $validatedData['photo'] = $this->storePhoto($request->file('photo'));
            }

            // Update the committee record
            $committee->update($validatedData);

            flash(__('committee.updated'))->success();
            return redirect()->route('committee.index');
        } catch (PostTooLargeException $e) {
            return back()->withErrors(['photo' => __('The uploaded file is too large. Please upload a smaller image.')])
                         ->withInput();
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error updating committee: ' . $e->getMessage());

            return back()->withErrors(['error' => __('An unexpected error occurred. Please try again.')])
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Committee $committee)
    {
        $this->deletePhoto($committee->photo);
        $committee->delete();

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
