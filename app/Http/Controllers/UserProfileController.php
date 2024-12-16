<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function edit($id)
    {
        $title = __('user_profile.title'); // Fetch the translated title
        return view('userprofile_edit', compact('title')); // Pass the title to the view
    }


    public function update(Request $request, $id)
    {
        // Validate the input fields, including password confirmation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|confirmed', // Ensures the password and confirmation match
        ]);

        // Prepare the data to be updated
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Check if the password is provided and encrypt it
        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        // Update the user data
        $user = auth()->user();
        $user->fill($data);
        $user->save();

        // Flash success message
        flash(__('cashflow.saved'))->success();

        // Redirect back to the profile edit page
        return back();
    }
}
