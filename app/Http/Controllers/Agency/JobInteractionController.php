<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobInteractionController extends Controller
{
public function like($jobPostId)
{
    $like = Like::where('user_id', Auth::id())
                ->where('job_post_id', $jobPostId)
                ->first();

    if ($like) {
        $like->delete(); 
        $liked = false;
    } else {
        Like::create([
            'user_id' => Auth::id(),
            'job_post_id' => $jobPostId,
        ]);
        $liked = true;
    }

    // Get updated like count
    $likesCount = Like::where('job_post_id', $jobPostId)->count();

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

        // Return JSON for AJAX
        return response()->json([
            'id' => $comment->id,
            'content' => $comment->content,
            'user_name' => Auth::user()->firstname ?? 'Unknown',
            'created_at' => 'just now', // for immediate display
        ]);
    }

}
