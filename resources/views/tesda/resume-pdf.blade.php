<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $resume->first_name }} {{ $resume->last_name }} - Resume</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h2, h3 { margin: 0; padding: 0; }
        .section { margin-bottom: 15px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>{{ $resume->first_name }} {{ $resume->middle_name ?? '' }} {{ $resume->last_name }}</h2>
    <p>{{ $resume->email }} | {{ $resume->phone }}</p>
    <p>{{ $resume->address }}, {{ $resume->city }}, {{ $resume->province }}, {{ $resume->zip_code }}</p>

    <div class="section">
        <h3>Professional Summary</h3>
        <p>{{ $resume->summary }}</p>
    </div>

    <div class="section">
        <h3>Education</h3>
        <p>{{ $resume->degree }} in {{ $resume->field_of_study }} - {{ $resume->school_name }} ({{ $resume->grad_year }})</p>
    </div>

    @if($resume->company_name)
    <div class="section">
        <h3>Experience</h3>
        <p>{{ $resume->job_title }} at {{ $resume->company_name }}</p>
        <p>{{ $resume->job_start_date }} - {{ $resume->job_end_date ?? 'Present' }}</p>
        <p>{{ $resume->job_description }}</p>
    </div>
    @endif

    <div class="section">
        <h3>Skills</h3>
        <p>{{ $resume->skills }}</p>
    </div>

    @if($resume->certification_name)
    <div class="section">
        <h3>Certifications</h3>
        <p>{{ $resume->certification_name }} - {{ $resume->certification_year }}</p>
    </div>
    @endif
</body>
</html>
