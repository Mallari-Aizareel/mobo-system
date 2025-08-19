<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\JobType;
use Illuminate\Support\Facades\Auth;

class TesdaHomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $jobPosts = JobPost::with('jobType', 'agency')
            ->when($search, function ($query, $search) {
                $query->where('job_position', 'like', "%{$search}%")
                      ->orWhere('job_description', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('tesda.home', compact('jobPosts', 'search'));
    }
}