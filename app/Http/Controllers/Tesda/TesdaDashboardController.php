<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnrolledTrainee;
use App\Models\Room;
use App\Models\Course;
use App\Models\TrainingCenter;
use Illuminate\Support\Facades\Auth;
use App\Models\Faq;

class TesdaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $enrolledCourses = EnrolledTrainee::with(['course', 'room.trainingCenter'])
            ->where('user_id', $user->id)
            ->when($search, function($query, $search) {
                $query->whereHas('course', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('room.trainingCenter', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->get();

        if ($request->ajax()) {
            return view('tesda.partials.enrolled_courses_grid', compact('enrolledCourses'))->render();
        }

        return view('tesda.dashboard', compact('enrolledCourses', 'search'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $enrollment = EnrolledTrainee::with(['course', 'room.trainingCenter'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('tesda.classes', compact('enrollment'));
    }

    public function faqs()
    {
        $faqs = Faq::all(); 
        return view('tesda.faqs', compact('faqs'));
    }
}