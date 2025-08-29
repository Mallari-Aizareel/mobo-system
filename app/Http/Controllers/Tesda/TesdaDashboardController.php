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
use App\Models\RoomModule;
use Illuminate\Support\Facades\Storage;

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

        $classmates = EnrolledTrainee::with(['user', 'course'])
            ->where('room_id', $enrollment->room_id)
            ->where('user_id', '!=', $user->id) 
            ->get();

        $modules = $enrollment->room->modules;

        return view('tesda.classes', compact('enrollment', 'classmates', 'modules'));
    }



    public function faqs()
    {
        $faqs = Faq::all(); 
        return view('tesda.faqs', compact('faqs'));
    }


public function downloadSelected(Request $request)
{
    \Log::info("✅ downloadSelected method started", $request->all());

    $request->validate([
        'module_ids' => 'required|array',
        'module_ids.*' => 'exists:room_modules,id',
    ]);

    $modules = RoomModule::whereIn('id', $request->module_ids)->get();
    \Log::info("📊 Modules found", ['count' => $modules->count()]);

    $tempPath = storage_path('app/temp');
    if (!file_exists($tempPath)) {
        mkdir($tempPath, 0755, true);
        \Log::info("📂 Created temp folder: $tempPath");
    }

    $zipName = 'modules_' . now()->format('YmdHis') . '.zip';
    $zipPath = $tempPath . DIRECTORY_SEPARATOR . $zipName;
    \Log::info("📦 Zip will be created at: $zipPath");

    $zip = new \ZipArchive;
    $openResult = $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

    if ($openResult === true) {
        foreach ($modules as $module) {
            $relativePath = $module->module_path;

            if (Storage::disk('public')->exists($relativePath)) {
                $absolutePath = Storage::disk('public')->path($relativePath);

                \Log::info("➕ Adding file: " . $absolutePath);
                $zip->addFile($absolutePath, basename($absolutePath));
            } else {
                \Log::error("❌ File not found: " . $relativePath);
                return back()->withErrors(['error' => "File not found: $relativePath"]);
            }
        }
        $zip->close();
        \Log::info("✅ Zip closed successfully");
    } else {
        \Log::error("❌ Could not open zip, error code: $openResult");
        dd("❌ Could not create zip file at $zipPath. Code: $openResult");
    }

    if (!file_exists($zipPath)) {
        \Log::error("❌ Zip not found after close: $zipPath");
        dd("❌ Zip not created", $zipPath);
    }

    return response()->download($zipPath)->deleteFileAfterSend(true);
}



    public function downloadModule($id)
    {
        $module = RoomModule::findOrFail($id);

        // Get the relative path (e.g. "modules/MoboSkills2.0.docx")
        $filePath = $module->module_path;

        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::download($filePath, basename($filePath));
    }
}