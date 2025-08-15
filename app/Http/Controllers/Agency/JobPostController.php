<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostController extends Controller
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

        $jobTypes = JobType::all();

        return view('agency.home', compact('jobPosts', 'jobTypes', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_qualifications' => 'nullable|string',
            'job_location' => 'required|string',
            'job_salary' => 'nullable|numeric',
            'job_type' => 'required|array',
            'job_type.*' => 'in:full_time,part_time,hybrid,remote,on_site,urgent,open_for_fresh_graduates',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $jobPosition = $request->input('job_title');

        $jobTypeFlags = [
            'full_time' => false,
            'part_time' => false,
            'hybrid' => false,
            'remote' => false,
            'on_site' => false,
            'urgent' => false,
            'open_for_fresh_graduates' => false,
        ];

        $selectedTypes = $request->input('job_type', []);
        foreach ($selectedTypes as $type) {
            if (array_key_exists($type, $jobTypeFlags)) {
                $jobTypeFlags[$type] = true;
            }
        }

        $jobType = JobType::create($jobTypeFlags);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('job_images', 'public');
        }

        JobPost::create([
            'agency_id' => Auth::id(),
            'job_position' => $jobPosition,
            'job_description' => $request->job_description,
            'job_qualifications' => $request->job_qualifications,
            'job_location' => $request->job_location,
            'job_salary' => $request->job_salary,
            'job_type_id' => $jobType->id,
            'job_image' => $imagePath,
        ]);

        return redirect()->route('agency.job-posts.index')->with('success', 'Job post created successfully.');
    }


    public function manage()
    {
        $jobPosts = JobPost::with('jobType', 'agency')
            ->where('agency_id', Auth::id())
            ->latest()
            ->paginate(10); 

        return view('agency.manage-posts', compact('jobPosts'));
    }

    public function edit($id)
    {
        $jobPost = JobPost::with('jobType')->where('agency_id', Auth::id())->findOrFail($id);

        return view('agency.job-edits', compact('jobPost'));
    }

    public function update(Request $request, $id)
    {
        $jobPost = JobPost::where('agency_id', Auth::id())->findOrFail($id);

        $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_qualifications' => 'nullable|string',
            'job_location' => 'required|string',
            'job_salary' => 'nullable|numeric',
            'job_type' => 'required|array',
            'job_type.*' => 'in:full_time,part_time,hybrid,remote,on_site,urgent,open_for_fresh_graduates',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $jobTypeFlags = [
            'full_time' => false,
            'part_time' => false,
            'hybrid' => false,
            'remote' => false,
            'on_site' => false,
            'urgent' => false,
            'open_for_fresh_graduates' => false,
        ];
        $selectedTypes = $request->input('job_type', []);
        foreach ($selectedTypes as $type) {
            if (array_key_exists($type, $jobTypeFlags)) {
                $jobTypeFlags[$type] = true;
            }
        }

        $jobPost->jobType()->update($jobTypeFlags);
        if ($request->hasFile('image')) {
            if ($jobPost->job_image) {
                \Storage::disk('public')->delete($jobPost->job_image);
            }
            $imagePath = $request->file('image')->store('job_images', 'public');
            $jobPost->job_image = $imagePath;
        }

        $jobPost->job_position = $request->job_title;
        $jobPost->job_description = $request->job_description;
        $jobPost->job_qualifications = $request->job_qualifications;
        $jobPost->job_location = $request->job_location;
        $jobPost->job_salary = $request->job_salary;
        $jobPost->save();

        return redirect()->route('agency.job-posts.manage')->with('success', 'Job post updated successfully.');
    }

    public function destroy($id)
    {
        $jobPost = JobPost::where('agency_id', Auth::id())->findOrFail($id);

        if ($jobPost->job_image) {
            \Storage::disk('public')->delete($jobPost->job_image);
        }

        $jobPost->jobType()->delete();

        $jobPost->delete();

        return redirect()->route('agency.job-posts.manage')->with('success', 'Job post deleted successfully.');
    }
}