@extends('adminlte::page')

@section('title', 'Job Posts')

@section('content_header')
    <h4><i class="fas fa-briefcase"></i> Job Posts</h4>
@stop

@section('content')
<div class="container">

    {{-- Create Post Button --}}
    <div class="card p-3 mb-4">
        <div class="d-flex align-items-center">
            {{-- Profile Image --}}
            <img src="{{ Auth::user()->profile_picture 
                ? asset('storage/' . Auth::user()->profile_picture) 
                : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->firstname . ' ' . Auth::user()->lastname) }}" 
                alt="Profile" 
                class="rounded-circle me-3 shadow-sm" 
                style="width: 45px; height: 45px; object-fit: cover;">
            <button class="btn btn-outline-primary w-100 text-start fw-semibold" data-toggle="modal" data-target="#createPostModal" style="font-size: 1rem;">
                <i class="fas fa-pencil-alt me-2"></i> Create Post
            </button>
        </div>
    </div>

    {{-- Modal for Posting Job --}}
    <div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="createPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Post a New Job</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('agency.job-posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Job Title</label>
                            <input type="text" name="job_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Job Description</label>
                            <textarea name="job_description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Qualifications</label>
                            <textarea name="job_qualifications" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Job Location</label>
                            <input type="text" name="job_location" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Salary</label>
                            <input type="number" name="job_salary" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Job Type</label>
                            <select name="job_type" class="form-control" required>
                                <option value="" disabled selected>Select Job Type</option>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="remote">Remote</option>
                                <option value="on_site">On Site</option>
                                <option value="urgent">Urgent</option>
                                <option value="open_for_fresh_graduates">Open for Fresh Graduates</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload Image (optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <div class="mt-2">
                                <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                            </div>
                        </div>
                        <button class="btn btn-success fw-semibold">Post Job</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Job Post Cards --}}
    @foreach($jobPosts as $job)
        <div class="card mb-5 shadow rounded-lg border-0" style="box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    {{-- Agency Profile --}}
                    <img src="{{ $job->agency->profile_picture 
                        ? asset('storage/' . $job->agency->profile_picture) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode($job->agency->name ?? 'Agency') }}" 
                        alt="Agency Profile" 
                        class="rounded-circle me-3 shadow" 
                        style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #0d6efd;">
                    
                    <div>
                        <a href="{{ route('agency.profile', $job->agency_id) }}" class="h4 mb-0 text-primary fw-bold" style="text-decoration: none;">
                            {{ $job->agency->name ?? 'Unknown Agency' }}
                        </a>
                        <div class="text-muted small fst-italic">{{ $job->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                {{-- Hiring text --}}
                <p class="text-muted fst-italic mb-1">We are now hiring:</p>

                {{-- Job Details --}}
                <div class="mb-4">
                    <h3 class="mb-3 fw-semibold text-dark">{{ $job->job_position }}</h3>

                    <p class="mb-2"><span class="fw-bold">Description:</span> <br>{!! nl2br(e($job->job_description)) !!}</p>

                    <p class="mb-2"><span class="fw-bold">Qualifications:</span> <br>{{ $job->job_qualifications ?? 'Not specified' }}</p>

                    <p class="mb-2"><span class="fw-bold">Location:</span> {{ $job->job_location ?? 'Not specified' }}</p>

                    <p class="mb-2"><span class="fw-bold">Salary:</span> 
                        {{ $job->job_salary ? '₱' . number_format($job->job_salary, 2) : 'Not specified' }}
                    </p>

                    <p class="mb-0"><span class="fw-bold">Job Type:</span> 
                        @php
                            $typeLabels = [
                                'full_time' => 'Full Time',
                                'part_time' => 'Part Time',
                                'hybrid' => 'Hybrid',
                                'remote' => 'Remote',
                                'on_site' => 'On Site',
                                'urgent' => 'Urgent',
                                'open_for_fresh_graduates' => 'Open for Fresh Graduates',
                            ];
                        @endphp
                        @if($job->jobType)
                            @php
                                $types = [];
                                foreach($typeLabels as $key => $label) {
                                    if ($job->jobType->$key) {
                                        $types[] = $label;
                                    }
                                }
                            @endphp
                            {{ implode(', ', $types) ?: 'Not specified' }}
                        @else
                            Not specified
                        @endif
                    </p>
                </div>

                {{-- Uploaded Image --}}
                @if($job->job_image)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $job->job_image) }}" alt="Job Image" 
                            class="img-fluid rounded shadow" 
                            style="max-height: 320px; object-fit: contain; border-radius: 12px;">
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="d-flex gap-3">
                    <button class="btn btn-outline-primary fw-semibold" style="flex: 1;">
                        <i class="fas fa-thumbs-up me-1"></i> Like
                    </button>
                    <button class="btn btn-outline-secondary fw-semibold" style="flex: 1;" data-toggle="collapse" data-target="#comments-{{ $job->id }}">
                        <i class="fas fa-comment me-1"></i> Comments
                    </button>
                </div>

                {{-- Comment Section --}}
                <div class="collapse mt-4" id="comments-{{ $job->id }}">
                    <div class="p-3 border rounded bg-light">
                        <strong>Recommended TESDA Graduates:</strong>
                        <ul class="list-group mt-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="#" class="fw-bold">Juan Dela Cruz</a>
                                    <br><a href="#">View Resume</a>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-envelope"></i> Send Message
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

{{-- Image Preview Script --}}
@push('js')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
@stop
