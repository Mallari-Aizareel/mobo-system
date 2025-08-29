<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobType;
use Illuminate\Http\Request;
use App\Models\JobRecommendation;
use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MutedAgency;
use App\Models\IgnoredAgencyNotification;

class JobPostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $mutedAgencyIds = MutedAgency::where('user_id', Auth::id())->pluck('agency_id');

        $jobPosts = JobPost::with(['recommendations' => function($q) {
                $q->where('match_score', '>=', 30);
            }, 'jobType', 'agency', 'comments.user'])
            ->when($search, function ($query, $search) {
                $query->where('job_position', 'like', "%{$search}%")
                    ->orWhere('job_description', 'like', "%{$search}%");
            })
            ->whereNotIn('agency_id', $mutedAgencyIds) 
            ->latest()
            ->get();

        $jobTypes = JobType::all();

        return view('agency.home', compact('jobPosts', 'jobTypes', 'search'));
    }


    public function mute($agencyId)
    {
        MutedAgency::firstOrCreate([
            'user_id' => Auth::id(),
            'agency_id' => $agencyId,
        ]);

        return back()->with('success', 'Agency muted successfully.');
    }

    public function ignore($agencyId)
    {
        IgnoredAgencyNotification::firstOrCreate([
            'user_id' => Auth::id(),
            'agency_id' => $agencyId,
        ]);

        return back()->with('success', 'Notifications ignored from this agency.');
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

        $jobPosts = JobPost::create([
            'agency_id' => Auth::id(),
            'job_position' => $jobPosition,
            'job_description' => $request->job_description,
            'job_qualifications' => $request->job_qualifications,
            'job_location' => $request->job_location,
            'job_salary' => $request->job_salary,
            'job_type_id' => $jobType->id,
            'job_image' => $imagePath,
        ]);

        $this->runResumeMatching($jobPosts);

        return redirect()->route('agency.job-posts.index')
            ->with('success', 'Job post created successfully. Matching resumes are being recommended.');
    }


    public function manage(Request $request)
    {
        $jobPosts = JobPost::with('jobType', 'agency')
            ->where('agency_id', Auth::id())
            ->latest()
            ->paginate(10); 

        $editJobId = $request->query('edit_job'); 

        return view('agency.manage-posts', compact('jobPosts', 'editJobId'));
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
    public function deleteComment($id)
{
    $comment = \App\Models\Comment::findOrFail($id);

    if ($comment->jobPost->agency_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $comment->delete();

    return back()->with('success', 'Comment deleted successfully.');
}

protected function runResumeMatching(JobPost $jobPost)
{
    $job = $jobPost;
    $resumes = \App\Models\Resume::all();

    foreach ($resumes as $resume) {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.sharpapi.key'),
            ])->attach(
                'file',
                file_get_contents(public_path('storage/' . ltrim($resume->pdf_path, '/'))),
                basename($resume->pdf_path)
            )->post('https://sharpapi.com/api/v1/hr/resume_job_match_score', [
                'content' => $job->job_position . "\n\n" . $job->job_description,
            ]);



            $result = $response->json();

            Log::info('API raw response', [
                'job_id' => $job->id,
                'resume' => $resume->pdf_path,
                'response' => $result,
            ]);

            $recommendation = \App\Models\JobRecommendation::create([
                'job_post_id' => $job->id,
                'user_id' => $resume->user_id,
                'resume_path' => $resume->pdf_path,
                'match_score' => 0, 
                'status_url' => $result['status_url'] ?? null, 
            ]);

            Log::info('Saved pending recommendation', [
                'job_id' => $job->id,
                'resume_id' => $resume->id,
                'user_id' => $resume->user_id,
                'resume_path' => $resume->pdf_path,
                'status_url' => $result['status_url'] ?? 'N/A',
            ]);

        } catch (\Exception $e) {
            Log::error('Resume match submission failed', [
                'job_id' => $job->id,
                'resume_id' => $resume->id,
                'error' => $e->getMessage(),
            ]);
            continue;
        }
    }

    Log::info('All resumes submitted for job', [
        'job_id' => $job->id,
        'total_resumes' => $resumes->count(),
    ]);

    $pending = \App\Models\JobRecommendation::where('match_score', 0)
        ->whereNotNull('status_url')
        ->get();

    foreach ($pending as $rec) {
        try {
            $statusResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.sharpapi.key'),
            ])->get($rec->status_url);

            $statusData = $statusResponse->json();
            Log::info('Status response', $statusData);

            if (isset($statusData['data']['attributes']['status']) && $statusData['data']['attributes']['status'] === 'success') {
                
                $resultJson = $statusData['data']['attributes']['result'] ?? '{}';
                $resultData = json_decode($resultJson, true);

                if (is_string($resultData)) {
                    $resultData = json_decode($resultData, true);
                }

                $overallMatch = $resultData['match_scores']['overall_match'] ?? 0;

                $rec->update([
                    'match_score' => $overallMatch,
                    'details' => json_encode($resultData['match_scores'] ?? []),
                ]);

                Log::info('Updated recommendation', [
                    'job_id' => $rec->job_post_id,
                    'user_id' => $rec->user_id,
                    'resume_path' => $rec->resume_path,
                    'match_score' => $overallMatch,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed polling recommendation', [
                'job_id' => $rec->job_post_id,
                'user_id' => $rec->user_id,
                'resume_path' => $rec->resume_path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    return back()->with('success', 'Resumes submitted and pending matches are being processed.');
}
}