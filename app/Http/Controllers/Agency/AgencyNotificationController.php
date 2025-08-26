<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobRecommendation;
use App\Models\JobPost;

class AgencyNotificationController extends Controller
{
    public function index(Request $request)
    {
        $userAgencyId = Auth::id();
        $filter = $request->query('filter'); // Get filter from query string

        // Recommendations for your job posts
        $recommendations = JobRecommendation::with(['user', 'jobPost'])
            ->whereHas('jobPost', function($q) use ($userAgencyId) {
                $q->where('agency_id', $userAgencyId);
            })
            ->when($filter, function($q) use ($filter) {
                if ($filter === 'new') {
                    $q->whereDate('created_at', now());
                } elseif ($filter === 'yesterday') {
                    $q->whereDate('created_at', now()->subDay());
                } elseif ($filter === '1_week') {
                    $q->where('created_at', '>=', now()->subWeek());
                } elseif ($filter === '2_weeks') {
                    $q->where('created_at', '>=', now()->subWeeks(2));
                } elseif ($filter === '1_month') {
                    $q->where('created_at', '>=', now()->subMonth());
                }
            })
            ->get()
            ->map(function($rec) {
                return [
                    'type' => 'recommendation',
                    'icon' => 'fas fa-robot text-primary',
                    'text' => ($rec->user->firstname ?? 'Unknown') . ' is recommended for your post: ' . ($rec->jobPost->job_position ?? 'Unknown Position'),
                    'created_at' => $rec->created_at,
                ];
            });

        $newPosts = JobPost::with('agency')
            ->where('agency_id', '<>', $userAgencyId)
            ->when($filter, function($q) use ($filter) {
                if ($filter === 'new') {
                    $q->whereDate('created_at', now());
                } elseif ($filter === 'yesterday') {
                    $q->whereDate('created_at', now()->subDay());
                } elseif ($filter === '1_week') {
                    $q->where('created_at', '>=', now()->subWeek());
                } elseif ($filter === '2_weeks') {
                    $q->where('created_at', '>=', now()->subWeeks(2));
                } elseif ($filter === '1_month') {
                    $q->where('created_at', '>=', now()->subMonth());
                }
            })
            ->get()
            ->map(function($post) {
                return [
                    'type' => 'new_post',
                    'icon' => 'fas fa-briefcase text-success',
                    'text' => ($post->agency->name ?? 'Unknown Agency') . ' added a new post: ' . ($post->job_position ?? 'Unknown Position'),
                    'created_at' => $post->created_at,
                ];
            });

        // Merge recommendations and new posts, sort by latest
        $notifications = $recommendations->merge($newPosts)->sortByDesc('created_at');

        return view('agency.notifications', compact('notifications', 'filter'));
    }
}
