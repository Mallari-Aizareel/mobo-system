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
                    <!-- Edit Button triggers modal -->
                    <button class="btn btn-primary fw-semibold" style="flex: 1;" data-toggle="modal" data-target="#editJobModal{{ $job->id }}">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>

                    <form action="{{ route('agency.job-posts.destroy', $job->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger fw-semibold w-100">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editJobModal{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="editJobModalLabel{{ $job->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
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
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Job Title</label>
                                <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $job->job_position) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Job Description</label>
                                <textarea name="job_description" class="form-control" rows="4" required>{{ old('job_description', $job->job_description) }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Qualifications</label>
                                <textarea name="job_qualifications" class="form-control" rows="3">{{ old('job_qualifications', $job->job_qualifications) }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Job Location</label>
                                <input type="text" name="job_location" class="form-control" value="{{ old('job_location', $job->job_location) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Salary</label>
                                <input type="number" name="job_salary" class="form-control" value="{{ old('job_salary', $job->job_salary) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Job Type</label>
                                <select name="job_type" class="form-control" required>
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
                                        $selectedType = '';
                                        if($job->jobType) {
                                            foreach($types as $key => $label) {
                                                if($job->jobType->$key) {
                                                    $selectedType = $key;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="" disabled>Select Job Type</option>
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}" {{ old('job_type', $selectedType) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Upload Image (optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewEditImage(event, {{ $job->id }})">
                                <div class="mt-2">
                                    <img id="editImagePreview{{ $job->id }}" src="{{ $job->job_image ? asset('storage/' . $job->job_image) : '#' }}" 
                                         alt="Image Preview" 
                                         style="max-width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); {{ $job->job_image ? '' : 'display:none;' }}">
                                </div>
                            </div>
                        </div>
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
