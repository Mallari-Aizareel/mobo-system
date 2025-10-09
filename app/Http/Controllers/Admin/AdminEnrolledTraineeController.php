<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnrolledTrainee;
use App\Models\Status;
use App\Models\UserCertificate;
use Carbon\Carbon;

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
        $statusFailed = Status::where('name', 'failed')->value('id');
        $statusGraduated = Status::where('name', 'graduated')->value('id');

        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'reason' => 'nullable|string', 
            // certificate fields only if graduated
            'nc_number' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:issued_date',
            'remarks' => 'nullable|in:valid,expired,renewed',
        ]);

        $trainee->update([
            'status_id' => $request->status_id,
            'reason' => $request->status_id == $statusFailed ? $request->reason : null,
        ]);

        // If the trainee is marked as graduated, create a certificate
        if ($request->status_id == $statusGraduated) {
            UserCertificate::updateOrCreate(
                [
                    'user_id' => $trainee->user_id,
                    'course_id' => $trainee->course_id,
                ],
                [
                    'nc_number' => $request->nc_number,
                    'issued_date' => $request->issued_date ? Carbon::parse($request->issued_date) : now(),
                    'expiration_date' => $request->expiration_date ? Carbon::parse($request->expiration_date) : null,
                    'status' => 'active',
                    'remarks' => $request->remarks ?? 'valid',
                ]
            );
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function graduates()
    {
        $statusGraduated = \App\Models\Status::where('name', 'graduated')->value('id');

        $graduates = \App\Models\EnrolledTrainee::with(['user.address', 'course', 'certificates'])
            ->where('status_id', $statusGraduated)
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
