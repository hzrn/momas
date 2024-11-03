<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\CategoryInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoController extends Controller
{
    // Other methods remain unchanged...

    public function index()
    {
        $info = Info::with('category')->MosqueUser()->latest()->paginate(10);
        return view('info_index', ['info' => $info, 'title' => __('info.title')]);
    }



    public function create()
    {
        $currentMosqueId = auth()->user()->mosque_id;
        $categoryList = CategoryInfo::where('mosque_id', $currentMosqueId)->pluck('name', 'id');

        return view('info_form', [
            'info' => new Info(),
            'route' => 'info.store',
            'method' => 'POST',
            'categoryList' => $categoryList,
            'title' => __('info.form_title')
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category_info_id' => 'required|exists:category_infos,id',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'content' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $imageName = uniqid() . '.' . $request->photo->extension();
            $request->photo->storeAs('public/infos', $imageName);
            $requestData['photo'] = $imageName;
        }

        Info::create($requestData);
        flash(__('info.saved'))->success();
        return redirect()->route('info.index');
    }

    public function show(Info $info)
    {
        $info->load('createdBy', 'updatedBy');

        return view('info_show', [
            'info' => $info,
            'title' => __('info.details_title'),

        ]);
    }


    public function edit(Info $info)
    {
        $categoryList = CategoryInfo::pluck('name', 'id');
        return view('info_form', [
            'info' => $info,
            'route' => ['info.update', $info->id],
            'method' => 'PUT',
            'categoryList' => $categoryList,
            'title' => __('info.edit_title')
        ]);
    }

    public function update(Request $request, Info $info)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category_info_id' => 'required|exists:category_infos,id',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'content' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($info->photo) {
                Storage::delete('public/infos/' . $info->photo);
            }
            $imageName = uniqid() . '.' . $request->photo->extension();
            $request->photo->storeAs('public/infos', $imageName);
            $requestData['photo'] = $imageName;
        }

        $info->update($requestData + ['updated_by' => auth()->id()]);
        flash(__('info.updated'))->success();
        return redirect()->route('info.index');
    }

    public function destroy(Info $info)
    {
        if ($info->photo) {
            Storage::delete('public/infos/' . $info->photo);
        }
        $info->delete();

        flash(__('info.deleted'))->success();
        return redirect()->route('info.index');
    }

    public function exportPDF()
    {
        $info = Info::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ?? __('info.no_mosque_assigned');

        $pdf = Pdf::loadView('info_pdf', compact('info', 'mosqueName'));
        return $pdf->download('info_list.pdf');
    }
}
