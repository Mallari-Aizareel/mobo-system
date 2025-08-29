<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\EnrolledTrainee;
use App\Models\Course;
use App\Models\TrainingCenter;
use Illuminate\Http\Request;
use App\Models\RoomModule;
use Illuminate\Support\Facades\Storage;

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
        $room = Room::with(['course', 'trainingCenter', 'modules.answers.user'])->findOrFail($id);

        $trainees = EnrolledTrainee::with(['user', 'course'])
            ->where('room_id', $room->id)
            ->get();

        return view('admin.room-info', compact('room', 'trainees'));
    }


    public function storeModule(Request $request, $roomId)
    {
        $request->validate([
            'module_file' => 'required|file|mimes:doc,docx,pdf,ppt,pptx,jpg,png,xls,xlsx,csv|max:10240',
        ]);

        $room = Room::findOrFail($roomId);

        $file = $request->file('module_file');
        $originalName = $file->getClientOriginalName();

        $path = $file->storeAs('modules', $originalName, 'public');

        RoomModule::create([
            'room_id' => $room->id,
            'module_path' => $path,
        ]);

        return back()->with('success', 'Module uploaded successfully!');
    }

    public function destroy($roomId, $moduleId)
    {
        $room = Room::findOrFail($roomId);
        $module = RoomModule::where('room_id', $room->id)->findOrFail($moduleId);

        if (Storage::exists($module->module_path)) {
            Storage::delete($module->module_path);
        }

        $module->delete();

        return back()->with('success', 'Module deleted successfully!');
    }

    
}