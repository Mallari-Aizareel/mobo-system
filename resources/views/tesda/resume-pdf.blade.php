{{-- <!DOCTYPE html>
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
</html> --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume - {{ $resume->first_name }} {{ $resume->last_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        h2, h3 {
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            color: #2c3e50;
        }
        p {
            margin: 3px 0;
        }
        .section {
            margin-bottom: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .header p {
            margin: 2px 0;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h2>{{ $resume->first_name }} {{ $resume->middle_name }} {{ $resume->last_name }}</h2>
        <p>Email: {{ $resume->email }} | Phone: {{ $resume->phone }}</p>
        <p>{{ $resume->address }}, {{ $resume->city }}, {{ $resume->province }} - {{ $resume->zip_code }}</p>
    </div>

    <!-- Summary -->
    <div class="section">
        <h3>Professional Summary</h3>
        <p>{{ $resume->summary }}</p>
    </div>

    <!-- Education -->
    <div class="section">
        <h3>Education</h3>
        <p><strong>{{ $resume->degree }}</strong> in {{ $resume->field_of_study }}</p>
        <p>{{ $resume->school_name }} | Graduated: {{ $resume->grad_year }}</p>
    </div>

    <!-- Work Experience -->
    @if($resume->company_name && $resume->job_title)
    <div class="section">
        <h3>Work Experience</h3>
        <p><strong>{{ $resume->job_title }}</strong> at {{ $resume->company_name }}</p>
        <p>{{ $resume->job_start_date }} - {{ $resume->job_end_date ?? 'Present' }}</p>
        <p>{{ $resume->job_description }}</p>
    </div>
    @endif

    <!-- Skills -->
    <div class="section">
        <h3>Skills</h3>
        <p>{{ $resume->skills }}</p>
    </div>

    <!-- Certifications -->
    @if($resume->certification_name)
    <div class="section">
        <h3>Certifications</h3>
        <p>{{ $resume->certification_name }} ({{ $resume->certification_year }})</p>
    </div>
    @endif

</body>
</html>
