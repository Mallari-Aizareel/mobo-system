<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobRecommendation;
use App\Models\JobPost;

class TesdaNotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $filter = $request->query('filter');

        // AI recommendations for this Tesda user
        $recommendations = JobRecommendation::with('jobPost.agency')
            ->where('user_id', $userId)
            ->whereNotNull('resume_path')      
            ->whereNotNull('match_score')             
            ->where('match_score', '>=', 60)   
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
                    'icon' => 'fas fa-robot text-info',
                    'text' => 'Recommended you for <strong>'.$rec->jobPost->job_position.'</strong> from '.$rec->jobPost->agency->name,
                    'created_at' => $rec->created_at,
                ];
            });

        // Agency posts
        $jobPosts = JobPost::with('agency')
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
                    'icon' => 'fas fa-bell text-primary',
                    'text' => 'New post from <strong>'.$post->agency->name.'</strong>: '.$post->job_position,
                    'created_at' => $post->created_at,
                ];
            });

        // Merge and sort
        $notifications = $recommendations->merge($jobPosts)->sortByDesc('created_at');

        return view('tesda.notifications', compact('notifications', 'filter'));
    }
}
