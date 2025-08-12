<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\EnrolledTrainee;
use App\Models\User;
use Illuminate\Support\Str;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */ 
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $courses = Course::all();

        $courseData = Course::withCount('enrolledTrainees')->get()->mapWithKeys(function ($course) {
            return [$course->name => $course->enrolled_trainees_count];
        });

        $traineesCount = EnrolledTrainee::where('status_id', 1)->count(); 
        $graduatesCount = EnrolledTrainee::where('status_id', 2)->count(); 
        $agenciesCount = User::where('role_id', 3)->count(); 

        $generatedColors = $courseData->keys()->map(function ($courseName) {
            return '#' . substr(md5($courseName), 0, 6); 
        })->toArray();

        return view('home', [
            'courses' => $courses,
            'courseData' => $courseData,
            'colors' => $generatedColors,
            'traineesCount' => $traineesCount,
            'graduatesCount' => $graduatesCount,
            'agenciesCount' => $agenciesCount,
        ]);
    }
}
