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
            $like->delete(); // Unlike if already liked
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'job_post_id' => $jobPostId,
            ]);
        }

        return back();
    }

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
