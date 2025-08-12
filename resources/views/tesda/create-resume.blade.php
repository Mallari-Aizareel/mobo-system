@extends('adminlte::page')

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">{{ isset($resume) ? 'Update Resume' : 'Create Resume' }}</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('tesda.resume.store') }}" method="POST">
                @csrf

                {{-- Personal Information --}}
                <h4>Personal Information</h4>
                <div class="row">
                    <div class="col-md-4">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $resume->first_name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $resume->middle_name ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $resume->last_name ?? '') }}" required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $resume->email ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $resume->phone ?? '') }}" required>
                    </div>
                </div>

                <div class="mt-2">
                    <label>Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $resume->address ?? '') }}" required>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label>City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $resume->city ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Province <span class="text-danger">*</span></label>
                        <input type="text" name="province" class="form-control" value="{{ old('province', $resume->province ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Zip Code <span class="text-danger">*</span></label>
                        <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $resume->zip_code ?? '') }}" required>
                    </div>
                </div>

                <h4 class="mt-4">Professional Summary <span class="text-danger">*</span></h4>
                <textarea name="summary" class="form-control" rows="3" required>{{ old('summary', $resume->summary ?? '') }}</textarea>

                <h4 class="mt-4">Education</h4>
                <div class="row">
                    <div class="col-md-4">
                        <label>School Name <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $resume->school_name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Degree <span class="text-danger">*</span></label>
                        <input type="text" name="degree" class="form-control" value="{{ old('degree', $resume->degree ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Field of Study <span class="text-danger">*</span></label>
                        <input type="text" name="field_of_study" class="form-control" value="{{ old('field_of_study', $resume->field_of_study ?? '') }}" required>
                    </div>
                </div>

                <div class="mt-2">
                    <label>Graduation Year <span class="text-danger">*</span></label>
                    <input type="number" name="grad_year" class="form-control" value="{{ old('grad_year', $resume->grad_year ?? '') }}" required>
                </div>

                <h4 class="mt-4">Experience</h4>
                <input type="text" name="company_name" class="form-control mb-2" value="{{ old('company_name', $resume->company_name ?? '') }}" placeholder="Company Name">
                <input type="text" name="job_title" class="form-control mb-2" value="{{ old('job_title', $resume->job_title ?? '') }}" placeholder="Job Title">
                <label>Start Date</label>
                <input type="date" name="job_start_date" class="form-control mb-2" value="{{ old('job_start_date', $resume->job_start_date ?? '') }}">
                <label>End Date</label>
                <input type="date" name="job_end_date" class="form-control mb-2" value="{{ old('job_end_date', $resume->job_end_date ?? '') }}">
                <textarea name="job_description" class="form-control" rows="3" placeholder="Job Description">{{ old('job_description', $resume->job_description ?? '') }}</textarea>

                <h4 class="mt-4">Skills <span class="text-danger">*</span></h4>
                <textarea name="skills" class="form-control" rows="2" required>{{ old('skills', $resume->skills ?? '') }}</textarea>

                <h4 class="mt-4">Certifications</h4>
                <input type="text" name="certification_name" class="form-control mb-2" value="{{ old('certification_name', $resume->certification_name ?? '') }}" placeholder="Certification Name">
                <input type="number" name="certification_year" class="form-control" value="{{ old('certification_year', $resume->certification_year ?? '') }}" placeholder="Certification Year">

                <div class="text-end">
                    <button type="submit" class="btn btn-primary mt-3">
                        {{ isset($resume) ? 'Update Resume' : 'Save Resume' }}
                    </button>
                </div>
            </form>
@endsection
