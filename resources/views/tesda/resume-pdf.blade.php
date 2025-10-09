<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $resume->first_name ?? 'N/A' }} {{ $resume->last_name ?? 'N/A' }} - Resume</title>
<style>
    html, body {
        width: 210mm;
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #333;
    }

    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; }

    /* Sidebar */
    .sidebar {
        width: 30%;
        padding: 20px;
        background-color: #1f2937;
        color: #fff;
    }
    .profile-name { font-size: 22px; font-weight: bold; margin-bottom: 5px; line-height: 1.2; }
    .job-title { font-size: 13px; color: #9ca3af; margin-bottom: 20px; }
    .section-title { font-size: 12px; text-transform: uppercase; margin: 20px 0 8px 0; font-weight: bold; border-bottom: 1px solid #fff; padding-bottom: 4px; }
    .contact-item { font-size: 12px; margin-bottom: 6px; line-height: 1.4; }
    .skill-item { display: inline-block; background-color: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 6px; margin: 2px 4px 4px 0; font-size: 12px; }

    /* Main Content */
    .main-content {
        width: 70%;
        padding: 20px;
    }
    .main-heading { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    .timeline-item { margin-bottom: 14px; }
    .timeline-item h3 { margin: 0; font-size: 13px; font-weight: bold; }
    .timeline-item .subtitle { font-size: 12px; margin: 2px 0; color: #555; }
    .timeline-item .date { font-size: 11px; color: #777; }
    .timeline-item ul { margin: 4px 0 0 15px; padding: 0; }
    .timeline-item ul li { font-size: 12px; margin-bottom: 3px; }

    /* Print adjustment */
    @media print { 
        .sidebar { -webkit-print-color-adjust: exact; print-color-adjust: exact; } 
    }
</style>
</head>
<body>
<table>
<tr>
    <!-- Sidebar -->
    <td class="sidebar" style="vertical-align:top;">
        <div class="profile-name">{{ $resume->first_name ?? '' }} {{ $resume->middle_name ?? '' }} {{ $resume->last_name ?? '' }}</div>
        <div class="job-title">{{ $resume->job_title ?? 'N/A' }}</div>

        <!-- Contact -->
        <div class="section-title">Contact</div>
        <div class="contact-item">TEL: {{ $resume->phone ?? 'N/A' }}</div>
        <div class="contact-item">EMAIL: {{ $resume->email ?? 'N/A' }}</div>
        <div class="contact-item">ADDR: {{ $resume->address ?? 'N/A' }}, {{ $resume->city ?? 'N/A' }}, {{ $resume->province ?? '' }}</div>

        <!-- Skills -->
        <div class="section-title">Skills</div>
        @if($resume->parsed_skills)
            @foreach(explode(',', $resume->parsed_skills) as $skill)
                <div class="skill-item">{{ trim($skill) }}</div>
            @endforeach
        @elseif($resume->skills)
            @foreach(explode(',', $resume->skills) as $skill)
                <div class="skill-item">{{ trim($skill) }}</div>
            @endforeach
        @else
            <div class="skill-item">N/A</div>
        @endif
    </td>

    <!-- Main Content -->
    <td class="main-content" style="vertical-align:top;">
        <!-- Professional Summary -->
        <div class="main-heading">Professional Summary</div>
        <div>{{ $resume->summary ?? 'N/A' }}</div>

        <!-- Work Experience -->
        <div class="main-heading">Work Experience</div>
        @if($resume->company_name)
            <div class="timeline-item">
                <h3>{{ $resume->company_name }}</h3>
                <div class="subtitle">{{ $resume->job_title ?? 'N/A' }}</div>
                <div class="date">{{ $resume->job_start_date ?? 'N/A' }} - {{ $resume->job_end_date ?? 'Present' }}</div>
                @if($resume->job_description)
                    <ul>
                        @foreach(explode("\n", $resume->job_description) as $desc)
                            <li>{{ $desc }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @else
            <p>N/A</p>
        @endif

        <!-- Education -->
        <div class="main-heading">Education</div>
        @if($resume->school_name)
            <div class="timeline-item">
                <h3>{{ $resume->school_name }}</h3>
                <div class="subtitle">{{ $resume->degree }} in {{ $resume->field_of_study }}</div>
                <div class="date">{{ $resume->grad_year ?? 'N/A' }}</div>
            </div>
        @else
            <p>N/A</p>
        @endif

        <!-- Certifications -->
        <div class="main-heading">Certifications</div>
        @if($resume->certification_name)
            <div class="timeline-item">
                <h3>{{ $resume->certification_name }}</h3>
                <div class="date">{{ $resume->certification_year ?? 'N/A' }}</div>
            </div>
        @else
            <p>N/A</p>
        @endif
    </td>
</tr>
</table>
</body>
</html>
