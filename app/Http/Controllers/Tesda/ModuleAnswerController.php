<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomModule;
use App\Models\ModuleAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleAnswerController extends Controller
{
    public function store(Request $request, RoomModule $module)
    {
        $request->validate([
            'answer_file' => 'required|file|mimes:doc,docx,pdf,ppt,pptx,jpg,png,xls,xlsx,csv|max:10240',
        ]);

        $file = $request->file('answer_file');
        $filename = Auth::id() . '_' . time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('answers', $filename, 'public');

        ModuleAnswer::updateOrCreate(
            [
                'module_id' => $module->id,
                'user_id' => Auth::id(),
            ],
            [
                'answer_path' => $path,
            ]
        );

        return back()->with('success', 'Answer uploaded successfully!');
    }

    public function download(ModuleAnswer $answer)
    {
        if (!Storage::disk('public')->exists($answer->answer_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($answer->answer_path, basename($answer->answer_path));
    }

}
