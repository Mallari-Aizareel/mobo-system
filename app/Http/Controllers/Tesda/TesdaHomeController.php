<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobType;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TesdaHomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $user = Auth::user();

        $mutedIds = $user->mutedUsers->pluck('id'); 

        $jobPosts = JobPost::with('jobType', 'agency')
            ->when($search, function ($query, $search) {
                $query->where('job_position', 'like', "%{$search}%")
                    ->orWhere('job_description', 'like', "%{$search}%");
            })
            ->whereNotIn('agency_id', $mutedIds)
            ->latest()
            ->get();


        return view('tesda.home', compact('jobPosts', 'search'));
    }

    public function toggleMute($agencyId)
{
    $user = Auth::user();

    if ($user->mutedUsers()->where('agency_id', $agencyId)->exists()) {
        // Already muted → unmute
        $user->mutedUsers()->detach($agencyId);
        return back()->with('success', 'Agency unmuted.');
    } else {
        // Not muted → mute
        $user->mutedUsers()->attach($agencyId);
        return back()->with('success', 'Agency muted.');
    }
}

public function toggleIgnore($agencyId)
{
    $user = Auth::user();

    if ($user->ignoredUsers()->where('agency_id', $agencyId)->exists()) {
        // Already ignored → unignore
        $user->ignoredUsers()->detach($agencyId);
        return back()->with('success', 'Agency notifications unignored.');
    } else {
        // Not ignored → ignore
        $user->ignoredUsers()->attach($agencyId);
        return back()->with('success', 'Agency notifications ignored.');
    }
}
}