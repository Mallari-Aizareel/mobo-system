<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use App\Models\AgencyFeedback;

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

public function comment(Request $request, $jobPostId)
{
    $request->validate([
        'content' => 'required|string|max:500'
    ]);

    $comment = Comment::create([
        'user_id' => Auth::id(),
        'job_post_id' => $jobPostId,
        'content' => $request->input('content'),
    ]);

    // Return JSON so AJAX can update the UI
    return response()->json([
        'id' => $comment->id,
        'content' => $comment->content,
        'user_name' => $comment->user->firstname ?? 'Unknown',
        'created_at' => $comment->created_at->diffForHumans(),
    ]);
}



public function likeAgency($agencyId)
{
    $feedback = AgencyFeedback::firstOrNew([
        'agency_id' => $agencyId,
        'user_id'   => auth()->id(),
    ]);

    // Toggle the like
    $feedback->liked = !$feedback->liked;
    $feedback->save();

    return back(); // reloads the page and updates the thumbs + count
}

}
