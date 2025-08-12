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
        // Get currently logged-in user (agency)
        $agency = Auth::user();

        // Pass the agency data to your profile.blade.php view
        return view('agency.profile', compact('agency'));
    }
    
    public function edit()
    {
        $user = Auth::user();

        // Load agency representatives linked to the user
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

            'profile_picture' => 'nullable|image|max:2048',       // agency logo
            'background_picture' => 'nullable|image|max:4096',    // agency cover photo

            // Representative fields for first rep
            'representative_first_name' => 'required|string|max:255',
            'representative_last_name' => 'required|string|max:255',
            'representative_phone_number' => 'required|string|max:20',
            'representative_email' => 'required|email|max:255',
        ]);

        // Update user's basic info (used as agency info here)
        $user->update([
            'firstname' => $validated['firstname'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            // You can handle address relationship separately if needed
        ]);

        $user->address()->updateOrCreate([], [
            'street'   => $request->street,
            'barangay' => $request->barangay,
            'city'     => $request->city,
            'province' => $request->province,
            'country'  => $request->country,
        ]);

        // Upload and update profile_picture (agency logo)
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Upload and update background_picture (agency cover photo)
        if ($request->hasFile('background_picture')) {
            if ($user->background_picture) {
                Storage::delete('public/' . $user->background_picture);
            }
            $user->background_picture = $request->file('background_picture')->store('background_pictures', 'public');
        }

        $user->save();

        // Update or create the first representative linked to agency (user)
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
}
