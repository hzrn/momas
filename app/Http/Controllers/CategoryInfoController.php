<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\CategoryInfo;



class CategoryInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryinfo = CategoryInfo::with('createdBy')->MosqueUser()->orderBy('created_at', 'desc');

        // Preprocess data for the Blade template
        $categoryinfo->map(function ($item) {
            $item->created_by_name = optional($item->createdBy)->name;
            return $item;
        });

        $title = __('categoryinfo.title');
        return view('categoryinfo_index', compact('categoryinfo', 'title'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryinfo = new CategoryInfo();
        $title = __('categoryinfo.form_title');
        return view('categoryinfo_form', compact('categoryinfo', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $categoryinfo = CategoryInfo::create($requestData);
        flash(__('categoryinfo.saved'))->success();
        return redirect()->route('categoryinfo.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $categoryinfo = CategoryInfo::findOrFail($id);
        $title = __('categoryinfo.edit_title');
        return view('categoryinfo_form', compact('categoryinfo', 'title'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoryinfo = CategoryInfo::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $categoryinfo->update($validatedData);

        flash(__('categoryinfo.updated'))->success();

        return redirect()->route('categoryinfo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoryinfo = CategoryInfo::findOrFail($id);
        $categoryinfo->delete();

        flash(__('categoryinfo.deleted'))->success();

        return redirect()->route('categoryinfo.index');
    }
}
