<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TesdaResumeController extends Controller
{
    public function index()
    {
        $resume = Resume::where('user_id', Auth::id())->first();
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

        $resume = Resume::where('user_id', Auth::id())->first();

        if ($resume) {
            $resume->update($validated);
            $message = 'Resume updated successfully!';
        } else {
            $validated['user_id'] = Auth::id();
            $resume = Resume::create($validated);
            $message = 'Resume created successfully!';
        }

        
        $pdfFolder = public_path('resumes');
        if (!file_exists($pdfFolder)) {
            mkdir($pdfFolder, 0755, true);
        }

        // Generate PDF
        $pdf = Pdf::loadView('tesda.resume-pdf', compact('resume'));
        $pdfPath = 'resumes/resume_' . Auth::id() . '.pdf';

        // Delete old PDF if exists
        if ($resume->pdf_path && file_exists(public_path($resume->pdf_path))) {
            unlink(public_path($resume->pdf_path));
        }

        // Save new PDF
        $pdf->save(public_path($pdfPath));

        // Update database with PDF path
        $resume->update(['pdf_path' => $pdfPath]);

        return redirect()->route('tesda.resume')->with('success', $message);
    }
}
