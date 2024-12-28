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
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Retrieve the authenticated user's mosque or create a new instance
        $mosque = auth()->user()->mosque ?? new Mosque();

        // Assign validated data to the mosque
        $mosque->name = $data['name'];
        $mosque->address = $data['address'];
        $mosque->phone_num = $data['phone_num'];
        $mosque->email = $data['email'];
        $mosque->latitude = $data['latitude'];
        $mosque->longitude = $data['longitude'];
        $mosque->save();

        // Associate the mosque with the user
        $user = auth()->user();
        $user->mosque_id = $mosque->id;
        $user->save();

        // Flash success message
        flash(__('mosque.saved'))->success();

        // Flash the location name to the session
        session(['location_name' => $request->input('location_name', 'No location selected')]);

        // Redirect back to the form with success message
        return redirect()->route('mosque.create');
    }


}
