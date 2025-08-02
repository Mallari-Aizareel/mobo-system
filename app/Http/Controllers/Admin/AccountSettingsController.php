<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Address;
use App\Models\Gender;
use App\Models\User;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $genders = Gender::all();

        return view('admin.account-settings', [
            'user' => $user,
            'genders' => $genders,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'birthdate'   => 'nullable|date',
            'religion'    => 'nullable|string|max:255',
            'gender_id'   => 'nullable|exists:genders,id',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string|max:11',

            'street'      => 'nullable|string|max:255',
            'barangay'    => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:255',
            'province'    => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:255',

            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'background_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        // Update or create address
        if ($user->address_id) {
            $address = Address::find($user->address_id);
            $address->update($request->only(['street', 'barangay', 'city', 'province', 'country']));
        } else {
            $address = Address::create($request->only(['street', 'barangay', 'city', 'province', 'country']));
            $user->address_id = $address->id;
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $profilePath = $file->storeAs('profile_pictures', $filename, 'public');
            $user->profile_picture = $profilePath;
        }

        if ($request->hasFile('background_picture')) {
            $file = $request->file('background_picture');
            $filename = time() . '_bg_' . $file->getClientOriginalName();
            $bgPath = $file->storeAs('background_pictures', $filename, 'public');
            $user->background_picture = $bgPath;
        }

        $user->update([
            'firstname'   => $validated['firstname'],
            'lastname'    => $validated['lastname'],
            'email'       => $validated['email'],
            'birthdate'   => $validated['birthdate'],
            'religion'    => $validated['religion'],
            'gender_id'   => $validated['gender_id'],
            'description' => $validated['description'],
            'phone_number' => $validated['phone_number'],
            'profile_picture' => $user->profile_picture,
            'background_picture' => $user->background_picture ?? $user->background_picture,
        ]);

        return redirect()->back()->with('success', 'Account settings updated successfully.');
    }


    public function upload(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePath;
        }

        if ($request->hasFile('background_picture')) {
            $bgPath = $request->file('background_picture')->store('background_picture', 'public');
            $user->background_picture = $bgPath;
        }

        $user->save();

        return redirect()->back()->with('success', 'Picture updated successfully.');
    }

}
