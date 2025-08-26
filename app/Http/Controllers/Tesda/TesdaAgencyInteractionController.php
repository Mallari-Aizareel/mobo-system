<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;

class TesdaAgencyInteractionController extends Controller
{
    // Like/unlike a job post
    public function like($jobPostId)
{
    $jobPost = JobPost::findOrFail($jobPostId); // Will throw 404 if not found

    $like = Like::where('user_id', Auth::id())
                ->where('job_post_id', $jobPost->id)
                ->first();

    if ($like) {
        $like->delete(); 
        $liked = false;
    } else {
        Like::create([
            'user_id' => Auth::id(),
            'job_post_id' => $jobPost->id,
        ]);
        $liked = true;
    }

    $likesCount = Like::where('job_post_id', $jobPost->id)->count();

    return response()->json([
        'likes_count' => $likesCount,
        'liked' => $liked,
    ]);
}

    // Add a comment
    public function comment(Request $request, $jobPostId)
    {
        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'job_post_id' => $jobPostId,
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Comment added successfully!');
    }
}
