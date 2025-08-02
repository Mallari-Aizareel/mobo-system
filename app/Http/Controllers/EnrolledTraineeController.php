<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Gender;
use App\Models\Agreement;
use App\Models\EnrolledTrainee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EnrolledAgreement;

class EnrolledTraineeController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $agreements = Agreement::all();
        $genders = Gender::all();
        $user = auth()->user();

        if ($user->role_id == 2) {
            $user->load('address', 'gender');
        }

        return view('tesda.enroll_courses', compact('genders','courses', 'agreements', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'valid_id' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'certificate' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'firstname' => 'required|string|max:100',
            'middlename' => 'nullable|string|max:100',
            'lastname' => 'required|string|max:100',
            'gender' => 'required|exists:genders,id',
            'birthdate' => 'required|date',
            'religion' => 'nullable|string|max:100',
            'phone' => 'required',
            'email' => 'required|email',
            'street' => 'required',
            'barangay' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
        ]);

        $user = auth()->user();

        $user->update([
            'firstname'   => $request->firstname,
            'middlename'  => $request->middlename,
            'lastname'    => $request->lastname,
            'gender_id'    => $request->gender,
            'birthdate'    => $request->birthdate,
            'religion'     => $request->religion,
            'email'        => $request->email,
            'phone_number' => $request->phone,
        ]);

        if ($user->address) {
            $user->address->update([
                'street'   => $request->street,
                'barangay' => $request->barangay,
                'city'     => $request->city,
                'province' => $request->province,
                'country'  => $request->country,
            ]);
        }

        $validIdPath = $request->file('valid_id')->store('valid_ids', 'public');
        $certPath = $request->file('certificate')->store('certificates', 'public');

        EnrolledTrainee::create([
            'user_id'    => $user->id,
            'course_id'  => $request->course_id,
            'valid_id'   => $validIdPath,
            'certificate'=> $certPath,
            'status_id' => 1,
        ]);

        foreach ($request->agreements as $agreementId => $answer) {
            EnrolledAgreement::create([
                'user_id'      => $user->id,
                'agreement_id' => $agreementId,
                'answer'       => $answer,
            ]);
        }

        return redirect()->back()->with('success', 'Enrollment submitted successfully!');
    }

}