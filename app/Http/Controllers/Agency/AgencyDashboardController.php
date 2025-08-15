<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq; 
use App\Models\EnrolledTrainee;
use App\Models\User;
use App\Models\Room;
use App\Models\Course;

class AgencyDashboardController extends Controller
{

    public function index()
    {
        $graduates = EnrolledTrainee::where('status_id', 2)
            ->with(['user', 'course', 'room.course']) 
            ->get();

        return view('agency.dashboard', compact('graduates'));
    }

    public function faqs()
    {
        // Retrieve all FAQs (you can filter if needed for agency)
        $faqs = Faq::all();

        // Return the agency/faqs view
        return view('agency.faqs', compact('faqs'));
    }
}
