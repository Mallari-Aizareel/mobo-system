<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AgencyRepresentative;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\JobPost;

class AgencyProfileController extends Controller
{
    public function index()
    {
        $agency = Auth::user()->load('address');

        $jobPosts = JobPost::with(['jobType', 'agency', 'likes', 'comments.user'])
            ->where('agency_id', $agency->id)
            ->latest()
            ->paginate(10);

        return view('agency.profile', compact('agency', 'jobPosts'));
    }


public function showForTesda($agencyId)
{
    // Fetch the user with role 'agency'
    $agency = User::where('id', $agencyId)->where('role_id', '3')->firstOrFail();

    // Fetch all their job posts for TESDA view
    $jobPosts = JobPost::with(['jobType', 'likes', 'comments.user'])
                        ->where('agency_id', $agency->id)
                        ->latest()
                        ->get();

    return view('agency.profile', compact('agency', 'jobPosts'));
}



    // public function showProfilePosts($agencyId)
    // {
    //     $jobPosts = JobPost::with('jobType', 'agency')
    //         ->where('agency_id', $agencyId)
    //         ->latest()
    //         ->paginate(10);

    //     return view('agency.profile', compact('jobPosts'));
    // }


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

    $jobPosts = JobPost::with(['jobType', 'likes', 'comments.user'])
                       ->where('agency_id', $agency->id)
                       ->latest()
                       ->get();

    $averageRating = $agency->average_rating;
    $myRating = $agency->myRating();
    $likesCount = $agency->likes_count;

    return view('agency.profile', compact('agency', 'jobPosts', 'averageRating', 'myRating', 'likesCount'));
}


    

}