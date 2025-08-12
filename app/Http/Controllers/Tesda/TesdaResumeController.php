<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;

class TesdaResumeController extends Controller
{
    public function index()
    {
        // Fetch the latest resume for this user (adjust logic as needed)
        $resume = Resume::first(); // If you have user_id, filter by it

        return view('tesda.create-resume', compact('resume'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal Info
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'province'   => 'required|string|max:255',
            'zip_code'   => 'required|string|max:20',

            // Summary
            'summary'    => 'required|string',

            // Education
            'school_name' => 'required|string|max:255',
            'degree'      => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'grad_year'   => 'required|digits:4',

            // Experience
            'company_name' => 'nullable|string|max:255',
            'job_title'    => 'nullable|string|max:255',
            'job_start_date' => 'nullable|date',
            'job_end_date'   => 'nullable|date',
            'job_description' => 'nullable|string',

            // Skills
            'skills' => 'required|string',

            // Certifications
            'certification_name' => 'nullable|string|max:255',
            'certification_year' => 'nullable|digits:4',
        ]);

        // Check if resume exists (adjust logic for user_id if needed)
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
