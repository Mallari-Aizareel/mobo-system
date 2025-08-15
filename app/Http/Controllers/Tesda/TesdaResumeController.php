<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;

class TesdaResumeController extends Controller
{
    public function index()
    {
        $resume = Resume::first();

        return view('tesda.create-resume', compact('resume'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'province'   => 'required|string|max:255',
            'zip_code'   => 'required|string|max:20',

            'summary'    => 'required|string',

            'school_name' => 'required|string|max:255',
            'degree'      => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'grad_year'   => 'required|digits:4',

            'company_name' => 'nullable|string|max:255',
            'job_title'    => 'nullable|string|max:255',
            'job_start_date' => 'nullable|date',
            'job_end_date'   => 'nullable|date',
            'job_description' => 'nullable|string',

            'skills' => 'required|string',

            'certification_name' => 'nullable|string|max:255',
            'certification_year' => 'nullable|digits:4',
        ]);

        $resume = Resume::first();

        if ($resume) {
            $resume->update($validated);
            $message = 'Resume updated successfully!';
        } else {
            Resume::create($validated);
            $message = 'Resume created successfully!';
        }

        return redirect()->route('tesda.resume')->with('success', $message);
    }
}