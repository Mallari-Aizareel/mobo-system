<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyFeedback;
use Illuminate\Http\Request;

class AgencyFeedbackController extends Controller
{
    /**
     * Toggle like (like/unlike).
     */
    public function like($agencyId)
    {
        $feedback = AgencyFeedback::firstOrNew([
            'agency_id' => $agencyId,
            'user_id'   => auth()->id(),
        ]);

        // Toggle the like (if already liked, unlike it; if not, like it)
        $feedback->liked = !$feedback->liked;
        $feedback->save();

        return back();
    }

    /**
     * Rate the agency (1–5 stars).
     */
    public function rate(Request $request, $agencyId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = AgencyFeedback::firstOrNew([
            'agency_id' => $agencyId,
            'user_id'   => auth()->id(),
        ]);

        $feedback->rating = $request->rating;
        $feedback->save();

        return back();
    }
}
