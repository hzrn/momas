<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function edit($id)
    {
        return view('userprofile_edit');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'nullable',
    ]);

    $data = [];

    if ($request->password != '') {
        $data['password'] = bcrypt($request->password);
    }

    $data['name'] = $request->name;
    $data['email'] = $request->email;

    $user = auth()->user();
    $user->fill($data);
    $user->save();

    flash('Data saved successfully')->success();

    return back();
}
}
