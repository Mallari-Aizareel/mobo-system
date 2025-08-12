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
                    <a href="{{ route('agency.job-edits', $job->id) }}" class="btn btn-primary fw-semibold" style="flex: 1;">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>

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
    @empty
        <div class="alert alert-info">No job posts found. Start by creating a new job post!</div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $jobPosts->links() }}
    </div>
</div>
@stop
