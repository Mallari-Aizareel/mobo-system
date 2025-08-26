<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\EnrolledTrainee;
use App\Models\Course;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $courses = Course::all();
        $trainingCenters = TrainingCenter::all();
        
        $trainees = EnrolledTrainee::with('user')->get();

        return view('admin.classes', compact('rooms', 'courses', 'trainingCenters', 'trainees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_option' => 'required',
            'room_name' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'training_center_id' => 'required|exists:training_centers,id',
            'trainee_ids' => 'required|array',
            'trainee_ids.*' => 'exists:users,id',
        ]);

        if ($request->room_option === 'new') {
            $room = Room::create([
                'name' => $request->room_name,
                'course_id' => $request->course_id,
                'training_center_id' => $request->training_center_id,
            ]);
        } else {
            $room = Room::findOrFail($request->room_option);
        }

        foreach ($request->trainee_ids as $traineeId) {
            $currentTraineesCount = EnrolledTrainee::where('room_id', $room->id)->count();
            if ($currentTraineesCount >= 30) {
                return back()->withErrors(['trainee_ids' => 'This room already has 30 trainees, which is the maximum allowed.']);
            }

            EnrolledTrainee::updateOrCreate(
                ['user_id' => $traineeId],
                ['room_id' => $room->id]
            );
        }

        return back()->with('success', 'Room and trainee saved successfully!');
    }


    public function classesList()
    {
        $rooms = Room::all();

        return view('admin.classes-list', compact('rooms'));
    }

    public function show($id)
    {
        $room = Room::with(['course', 'trainingCenter'])->findOrFail($id);

        $trainees = EnrolledTrainee::with(['user', 'course'])
            ->where('room_id', $room->id)
            ->get();

        return view('admin.room-info', compact('room', 'trainees'));
    } 
}