<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnrolledTrainee;
use App\Models\Status;

class AdminEnrolledTraineeController extends Controller
{
    public function index()
    {
        $statusPending = Status::where('name', 'pending')->value('id'); 

        $trainees = EnrolledTrainee::with(['user.address', 'course'])
            ->where('status_id', 1) 
            ->get();

        $statusGraduated = Status::where('name', 'graduated')->value('id');
        $statusFailed = Status::where('name', 'failed')->value('id');

        return view('admin.manage-enrolled-trainees', compact('trainees', 'statusGraduated', 'statusFailed'));
    }

    public function updateStatus(Request $request, EnrolledTrainee $trainee)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        $trainee->update([
            'status_id' => $request->status_id,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function graduates()
    {
        $graduates = \App\Models\EnrolledTrainee::with(['user.address', 'course'])
            ->where('status_id', 2)
            ->get();

        return view('admin.tesda-graduates', compact('graduates'));
    }

    public function showFailed()
    {
        $statusFailed = Status::where('name', 'failed')->value('id');

        $trainees = EnrolledTrainee::with(['user.address', 'course'])
            ->where('status_id', $statusFailed)
            ->get();

        return view('admin.drafted-trainees', compact('trainees'));
    }


}
