<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AgencyRepresentative;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgencyProfileController extends Controller
{
    public function index()
    {
        $agency = Auth::user()->load('address');
        return view('agency.profile', compact('agency'));
    }

    public function edit()
    {
        $user = Auth::user();

        $representatives = $user->agencyRepresentatives()->get();

        return view('agency.edit-info', compact('user', 'representatives'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',

            'profile_picture' => 'nullable|image|max:2048',      
            'background_picture' => 'nullable|image|max:4096',   

            'representative_first_name' => 'required|string|max:255',
            'representative_last_name' => 'required|string|max:255',
            'representative_phone_number' => 'required|string|max:20',
            'representative_email' => 'required|email|max:255',
        ]);

        $user->update([
            'firstname' => $validated['firstname'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
        ]);

        $user->address()->updateOrCreate([], [
            'street'   => $request->street,
            'barangay' => $request->barangay,
            'city'     => $request->city,
            'province' => $request->province,
            'country'  => $request->country,
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        if ($request->hasFile('background_picture')) {
            if ($user->background_picture) {
                Storage::delete('public/' . $user->background_picture);
            }
            $user->background_picture = $request->file('background_picture')->store('background_pictures', 'public');
        }

        $user->save();

        AgencyRepresentative::updateOrCreate(
            ['agency_id' => $user->id],
            [
                'first_name' => $validated['representative_first_name'],
                'last_name' => $validated['representative_last_name'],
                'phone_number' => $validated['representative_phone_number'],
                'email' => $validated['representative_email'],
            ]
        );

        return redirect()->route('agency.edit-info')->with('success', 'Agency profile and representative updated successfully!');
    }

public function show($id)
{
    $agency = User::findOrFail($id);

    $averageRating = $agency->average_rating;
    $myRating = $agency->myRating();
    $likesCount = $agency->likes_count;

    return view('agency.profile', compact('agency', 'averageRating', 'myRating', 'likesCount'));
}

    

}