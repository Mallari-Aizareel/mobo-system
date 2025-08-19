@extends('adminlte::page')

@section('title', 'Manage Job Posts')

@section('content_header')
    <h4><i class="fas fa-briefcase"></i> Manage Job Posts</h4>
@stop

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($jobPosts as $job)
        <div class="card mb-4 shadow-sm rounded-lg border-0" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div class="card-body p-4">

                {{-- Agency Info + Options --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $job->agency->profile_picture 
                            ? asset('storage/' . $job->agency->profile_picture) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($job->agency->name ?? 'Agency') }}" 
                            alt="Agency" class="rounded-circle me-3 shadow" style="width:50px; height:50px; object-fit:cover; border:2px solid #0d6efd;">
                        <div>
                            <a href="{{ route('agency.profile', $job->agency_id) }}" class="fw-bold text-primary mb-0 d-block" style="text-decoration:none;">
                                {{ $job->agency->name ?? 'Unknown Agency' }}
                            </a>
                            <small class="text-muted fst-italic">{{ $job->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    {{-- Ellipsis Dropdown with Edit/Delete --}}
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" id="optionsMenu{{ $job->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="optionsMenu{{ $job->id }}">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editJobModal{{ $job->id }}">Edit Post</a>
                            <form action="{{ route('agency.job-posts.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete Post</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Job Info --}}
                <h5 class="fw-bold mb-2">{{ $job->job_position }}</h5>
                <span class="badge bg-secondary me-2"><i class="fas fa-map-marker-alt me-1"></i> {{ $job->job_location ?? 'Not specified' }}</span>
                <div class="mb-2">
                    @if($job->jobType)
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
                            $types = [];
                            foreach($typeLabels as $key => $label){
                                if($job->jobType->$key) $types[] = $label;
                            }
                        @endphp
                        @foreach($types as $type)
                            <span class="badge bg-info text-dark me-1">{{ $type }}</span>
                        @endforeach
                    @endif
                </div>

                {{-- Description & Qualifications --}}
                <p class="mb-2"><span class="fw-bold">Description:</span> {!! nl2br(e($job->job_description)) !!}</p>
                <p class="mb-2"><span class="fw-bold">Qualifications:</span> {{ $job->job_qualifications ?? 'Not specified' }}</p>
                @if($job->job_salary)
                    <p class="mb-2"><span class="fw-bold">Salary:</span> ₱{{ number_format($job->job_salary,2) }}</p>
                @endif

                {{-- Image --}}
                @if($job->job_image)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/' . $job->job_image) }}" class="img-fluid rounded shadow-sm" style="max-height:300px; object-fit:cover;">
                    </div>
                @endif

{{-- Like / Comments Row --}}
<div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
    <div class="d-flex align-items-center text-primary fw-semibold" style="cursor:pointer;">
        <i class="fas fa-thumbs-up me-2"></i> 
        {{ $job->likes->count() }} Likes
    </div>
    <div class="text-secondary fw-semibold" style="cursor:pointer;" data-toggle="collapse" data-target="#comments-{{ $job->id }}">
        {{ $job->comments->count() }} Comments
    </div>
</div>

{{-- Comment Section --}}
<div class="collapse mt-3" id="comments-{{ $job->id }}">
    <div class="p-3 border rounded bg-light">
        <strong>Comments:</strong>
        <ul class="list-group mt-2">
            @forelse($job->comments as $comment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong><br>
                        {{ $comment->content }}
                        <br><small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <form action="{{ route('agency.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </li>
            @empty
                <li class="list-group-item text-muted">No comments yet.</li>
            @endforelse
        </ul>
    </div>
</div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editJobModal{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="editJobModalLabel{{ $job->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editJobModalLabel{{ $job->id }}">Edit Job Post</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('agency.job-posts.update', $job->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Job Title -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Job Title</label>
                                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $job->job_position) }}" required>
                                </div>

                                <!-- Job Location -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Job Location</label>
                                    <input type="text" name="job_location" class="form-control" value="{{ old('job_location', $job->job_location) }}" required>
                                </div>

                                <!-- Salary -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Salary</label>
                                    <input type="number" name="job_salary" class="form-control" value="{{ old('job_salary', $job->job_salary) }}">
                                </div>

                                <!-- Image Upload -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Upload Image (optional)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewEditImage(event, {{ $job->id }})">
                                    <div class="mt-2">
                                        <img id="editImagePreview{{ $job->id }}" src="{{ $job->job_image ? asset('storage/' . $job->job_image) : '#' }}" 
                                            alt="Image Preview" 
                                            style="max-width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); {{ $job->job_image ? '' : 'display:none;' }}">
                                    </div>
                                </div>

                                <!-- Description (full-width) -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Job Description</label>
                                    <textarea name="job_description" class="form-control" rows="4" required>{{ old('job_description', $job->job_description) }}</textarea>
                                </div>

                                <!-- Qualifications (full-width) -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Qualifications</label>
                                    <textarea name="job_qualifications" class="form-control" rows="3">{{ old('job_qualifications', $job->job_qualifications) }}</textarea>
                                </div>

                                <!-- Job Type (checkboxes, full-width) -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Job Type</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                            $types = [
                                                'full_time' => 'Full Time',
                                                'part_time' => 'Part Time',
                                                'hybrid' => 'Hybrid',
                                                'remote' => 'Remote',
                                                'on_site' => 'On Site',
                                                'urgent' => 'Urgent',
                                                'open_for_fresh_graduates' => 'Open for Fresh Graduates',
                                            ];
                                            $jobTypeChecked = $job->jobType ?? null;
                                        @endphp
                                        @foreach($types as $key => $label)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="job_type[]" value="{{ $key }}" 
                                                    id="jobType{{ $job->id }}{{ $key }}"
                                                    {{ $jobTypeChecked && $jobTypeChecked->$key ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jobType{{ $job->id }}{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div> <!-- row -->

                        </div> <!-- modal-body -->

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success fw-semibold">Update Job</button>
                            <button type="button" class="btn btn-secondary fw-semibold" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @empty
        <div class="alert alert-info">No job posts found. Start by creating a new job post!</div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $jobPosts->links() }}
    </div>
</div>

@push('js')
<script>
function previewEditImage(event, jobId) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('editImagePreview' + jobId);
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush

@stop
