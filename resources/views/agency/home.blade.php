@extends('adminlte::page')

@section('title', 'Job Posts')

@section('content_header')
    <h4> </h4>
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

    {{-- Modal for Creating Job Post --}}
    <div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="createPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createPostModalLabel">Post a New Job</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('agency.job-posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Job Title -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Job Title</label>
                                <input type="text" name="job_title" class="form-control" required>
                            </div>

                            <!-- Job Location -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Job Location</label>
                                <input type="text" name="job_location" class="form-control" required>
                            </div>

                            <!-- Salary -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Salary</label>
                                <input type="number" name="job_salary" class="form-control">
                            </div>

                            <!-- Image Upload -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Upload Image (optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <div class="mt-2">
                                    <img id="imagePreview" src="#" alt="Image Preview" 
                                        style="display: none; max-width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                </div>
                            </div>

                            <!-- Job Description (full-width) -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Job Description</label>
                                <textarea name="job_description" class="form-control" rows="4" required></textarea>
                            </div>

                            <!-- Qualifications (full-width) -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Qualifications</label>
                                <textarea name="job_qualifications" class="form-control" rows="3"></textarea>
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
                                    @endphp
                                    @foreach($types as $key => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="job_type[]" value="{{ $key }}" id="jobTypeCreate{{ $key }}">
                                            <label class="form-check-label" for="jobTypeCreate{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div> <!-- row -->
                    </div> <!-- modal-body -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success fw-semibold">Post Job</button>
                        <button type="button" class="btn btn-secondary fw-semibold" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@foreach($jobPosts as $job)
<div class="card mb-4 shadow-sm rounded-lg border-0">

    <div class="card-body p-4">

        {{-- Agency Info + Ellipsis --}}
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

            {{-- Ellipsis dropdown --}}
            <div class="dropdown">
    <button class="btn btn-link text-muted p-0" type="button" id="optionsMenu{{ $job->id }}" 
        data-bs-toggle="dropdown" aria-expanded="false"
        onclick="event.stopPropagation();">
        <i class="fas fa-ellipsis-h"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="optionsMenu{{ $job->id }}">
        @if(Auth::id() === $job->agency_id)
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editJobModal{{ $job->id }}">Edit Post</a>
            <form action="{{ route('agency.job-posts.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item text-danger">Delete Post</button>
            </form>
        @else
            <a class="dropdown-item" href="#">Mute this Agency</a>
            <a class="dropdown-item" href="#">Ignore notifications from this Agency</a>
        @endif
    </div>
</div>

        </div>

        {{-- Clickable area for modal --}}
        <div class="job-clickable-area" data-toggle="modal" data-target="#jobModal{{ $job->id }}" style="cursor:pointer;">
            {{-- Job Details --}}
            <h5 class="fw-bold mb-2">{{ $job->job_position }}</h5>
            <span class="badge bg-secondary me-2 mb-2"><i class="fas fa-map-marker-alt me-1"></i> {{ $job->job_location ?? 'Not specified' }}</span>
            <div class="mb-3">
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
                        <span class="badge bg-info text-dark job-type-badge">{{ $type }}</span>
                    @endforeach
                @endif
            </div>

            {{-- Full-width Image --}}
            @if($job->job_image)
                <img src="{{ asset('storage/' . $job->job_image) }}" class="img-fluid rounded shadow-sm mb-3" style="object-fit:cover; width:100%; max-height:400px;">
            @endif
        </div>


                  {{-- Like Button --}}
<form action="{{ route('agency.like', $job->id) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-outline-primary btn-sm">
        👍 Like ({{ $job->likes->count() }})
    </button>
</form>

{{-- Comments --}}
<div class="mt-3">
    <form action="{{ route('agency.comment', $job->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Write a comment..." required>
            <button class="btn btn-primary">Post</button>
        </div>
    </form>

    Show comments
    <div class="mt-2">
        @foreach($job->comments as $comment)
            <div class="border p-2 mb-1 rounded">
                <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        @endforeach
    </div>
</div>

        {{-- Like / Comments
        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
            <div class="d-flex align-items-center text-primary fw-semibold like-btn" style="cursor:pointer;">
                <i class="fas fa-thumbs-up me-2"></i> Like
            </div>
            <div class="text-secondary fw-semibold comment-btn" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#comments-{{ $job->id }}">
                <i class="fas fa-comment me-2"></i> Comment
            </div>
        </div> --}}

        {{-- Comment Section
        <div class="collapse mt-3" id="comments-{{ $job->id }}">
            <div class="p-3 border rounded bg-light">
                <strong>Recommended TESDA Graduates:</strong>
                <ul class="list-group mt-2">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="#" class="fw-bold">Juan Dela Cruz</a>
                            <br><a href="#">View Resume</a>
                        </div>
                        <button class="btn btn-primary btn-sm"><i class="fas fa-envelope"></i> Send Message</button>
                    </li>
                </ul>
                <input type="text" class="form-control mt-2" placeholder="Write a comment...">
            </div>
        </div>
    </form>  --}}

    </div> <!-- card-body -->

</div>


{{-- Job Modal --}}
<div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" aria-labelledby="jobModalLabel{{ $job->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jobModalLabel{{ $job->id }}">{{ $job->job_position }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
          {{-- Agency Info --}}
          <div class="d-flex align-items-center mb-3">
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

          {{-- Job Image --}}
          @if($job->job_image)
              <div class="text-center mb-3">
                  <img src="{{ asset('storage/' . $job->job_image) }}" class="img-fluid rounded shadow-sm" style="max-height:300px; object-fit:cover;">
              </div>
          @endif

          {{-- Job Details --}}
          <p><span class="fw-bold">Description:</span> {!! nl2br(e($job->job_description)) !!}</p>
          <p><span class="fw-bold">Qualifications:</span> {{ $job->job_qualifications ?? 'Not specified' }}</p>
          @if($job->job_salary)
              <p><span class="fw-bold">Salary:</span> ₱{{ number_format($job->job_salary,2) }}</p>
          @endif
          <p><span class="fw-bold">Location:</span> {{ $job->job_location ?? 'Not specified' }}</p>
          <p>
              @if($job->jobType)
                  @foreach($types as $type)
                      <span class="badge bg-info text-dark job-type-badge">{{ $type }}</span>
                  @endforeach
              @endif
          </p>


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

@section('css')
<style>
    .job-type-badge {
    border-radius: 50px;
    padding: 0.35rem 0.75rem;
    font-size: 0.85rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: inline-block;
    margin-bottom: 4px;
}

.job-type-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@stop
