@extends('adminlte::page')

@section('title', 'Job Posts')

@section('content_header')
    <h4>Job Posts</h4>
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
            <button class="btn btn-outline-primary w-100 text-start fw-semibold" data-bs-toggle="modal" data-bs-target="#createPostModal" style="font-size: 1rem;">
                <i class="fas fa-pencil-alt me-2"></i> Create Post
            </button>
        </div>
    </div>

    {{-- Modal for Creating Job Post --}}
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createPostModalLabel">Post a New Job</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('agency.job-posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            {{-- Job Title --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Job Title</label>
                                <input type="text" name="job_title" class="form-control" required>
                            </div>
                            {{-- Job Location --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Job Location</label>
                                <input type="text" name="job_location" class="form-control" required>
                            </div>
                            {{-- Salary --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Salary</label>
                                <input type="number" name="job_salary" class="form-control">
                            </div>
                            {{-- Image Upload --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Upload Image (optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <div class="mt-2">
                                    <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                </div>
                            </div>
                            {{-- Job Description --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Job Description</label>
                                <textarea name="job_description" class="form-control" rows="4" required></textarea>
                            </div>
                            {{-- Qualifications --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Qualifications</label>
                                <textarea name="job_qualifications" class="form-control" rows="3"></textarea>
                            </div>
                            {{-- Job Type --}}
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
                        <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@foreach($jobPosts as $job)
<div class="card mb-4 shadow-sm rounded-lg border-0">
    <div class="card-body p-3">
        {{-- Card Header: Agency Info + Ellipsis Menu --}}
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center">
                <img src="{{ $job->agency->profile_picture 
                    ? asset('storage/' . $job->agency->profile_picture) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($job->agency->firstname ?? 'Agency') }}" 
                    alt="Agency" class="rounded-circle me-2 shadow" style="width:40px; height:40px; object-fit:cover; border:2px solid #0d6efd;">
                <div>
                    <a href="{{ route('agency.show', $job->agency_id) }}" class="fw-bold text-primary d-block" style="text-decoration:none; font-size:0.95rem;">
                        {{ $job->agency->firstname ?? 'Unknown Agency' }}
                    </a>
                    <small class="text-muted fst-italic" style="font-size:0.75rem;">{{ $job->created_at->diffForHumans() }}</small>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn btn-link text-muted p-0" type="button" id="optionsMenu{{ $job->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="optionsMenu{{ $job->id }}">
                    @if(Auth::id() === $job->agency_id)
                        {{-- Redirect to Manage Posts with query param job_id --}}
                        <a class="dropdown-item" href="{{ route('agency.manage-posts', ['edit_job' => $job->id]) }}">
                            Edit Post
                        </a>

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

        {{-- Job Clickable Area --}}
        <div class="job-clickable-area" data-bs-toggle="modal" data-bs-target="#jobModal{{ $job->id }}" style="cursor:pointer;">
            <h6 class="fw-bold mb-1">{{ $job->job_position }}</h6>
            <span class="badge bg-secondary me-1 mb-1" style="font-size:0.75rem;">
                <i class="fas fa-map-marker-alt me-1"></i> {{ $job->job_location ?? 'Not specified' }}
            </span>
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
                            'open_for_fresh_graduates' => 'Fresh Grad',
                        ];
                        $types = [];
                        foreach($typeLabels as $key => $label){
                            if($job->jobType->$key) $types[] = $label;
                        }
                    @endphp
                    @foreach($types as $type)
                        <span class="badge bg-info text-dark me-1 mb-1" style="font-size:0.7rem;">{{ $type }}</span>
                    @endforeach
                @endif
            </div>

            @if($job->job_image)
                <img src="{{ asset('storage/' . $job->job_image) }}" class="img-fluid rounded shadow-sm mb-2" style="object-fit:cover; width:100%; max-height:250px;">
            @endif
        </div>

        {{-- Actions: Like Button --}}
        <div class="d-flex align-items-center mb-2">
            <form class="d-inline like-form" data-job-id="{{ $job->id }}">
                @csrf
                <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center like-btn" style="gap: 0.3rem;">
                    <i class="fas fa-thumbs-up"></i>
                    <span class="like-count">{{ $job->likes->count() }}</span>
                </button>
            </form>
        </div>

{{-- Comments & Recommendations --}}
<div class="mt-2">
    <form action="{{ route('agency.comment', $job->id) }}" method="POST" class="mb-2">
        @csrf
        <div class="input-group input-group-sm">
            <input type="text" name="content" class="form-control" placeholder="Write a comment..." required>
            <button class="btn btn-primary btn-sm">Post</button>
        </div>
    </form>

    {{-- Show resumes matched at least 30% --}}
    @php
        $matchedResumes = $job->recommendations->where('match_score', '>=', 30);
    @endphp

    @if($matchedResumes->isNotEmpty())
        @foreach ($matchedResumes as $rec)
            <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2 bg-light">
                <div>
                    <strong style="font-size:0.9rem;">{{ $rec->user->firstname ?? 'Unknown' }}</strong>
                    <small class="text-muted">Matched for this job</small>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-1">
                    {{-- View Resume --}}
                    <button type="button" class="btn btn-sm btn-primary p-1" 
                            data-bs-toggle="modal" 
                            data-bs-target="#resumeModal" 
                            data-resume="{{ asset('storage/'.$rec->resume_path) }}?v={{ time() }}" 
                            data-name="{{ $rec->user->firstname ?? 'Unknown' }}">
                        <i class="fas fa-file-alt"></i>
                    </button>

                    {{-- Message --}}
                    <button class="btn btn-outline-primary btn-sm p-1" 
                            data-bs-toggle="modal" 
                            data-bs-target="#messageModal{{ $rec->user_id }}">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>

            {{-- Message Modal --}}
            <div class="modal fade" id="messageModal{{ $rec->user_id }}" tabindex="-1" aria-labelledby="messageModalLabel{{ $rec->user_id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="messageModalLabel{{ $rec->user_id }}">
                                Send Message to {{ $rec->user->firstname }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('agency.messages-store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $rec->user_id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Message</label>
                                    <textarea name="message" class="form-control" rows="4" placeholder="Write your message..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-sm">Send</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted"></p>
    @endif

    {{-- Comments --}}
    @foreach($job->comments as $comment)
        <div class="p-2 border rounded mb-2 bg-white">
            <strong style="font-size:0.9rem;">{{ $comment->user->firstname ?? 'Unknown' }}</strong>
            <small class="text-muted">• {{ $comment->created_at->diffForHumans() }}</small>
            <div>{{ $comment->content }}</div>
        </div>
    @endforeach
</div>


    </div>
</div>

{{-- Job Modal --}}
<div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" aria-labelledby="jobModalLabel{{ $job->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jobModalLabel{{ $job->id }}">{{ $job->job_position }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

{{-- Resume Modal --}}
<div class="modal fade" id="resumeModal" tabindex="-1" aria-labelledby="resumeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resumeModalLabel">Resume</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="resumeFrame" src="" width="100%" height="100%" frameborder="0" style="border:0;"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection

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

    document.addEventListener('DOMContentLoaded', function () {
        const resumeModal = document.getElementById('resumeModal');
        const resumeFrame = document.getElementById('resumeFrame');
        const resumeModalLabel = document.getElementById('resumeModalLabel');

        resumeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const resumeUrl = button.getAttribute('data-resume') + '&v=' + new Date().getTime(); 
            const userName = button.getAttribute('data-name');
            resumeFrame.src = resumeUrl;
            resumeModalLabel.textContent = `Resume - ${userName}`;
        });

        resumeModal.addEventListener('hidden.bs.modal', function () {
            resumeFrame.src = ''; 
        });
    });
</script>
@endpush

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.like-btn').click(function(e) {
        e.preventDefault(); 
        var btn = $(this);
        var form = btn.closest('.like-form');
        var jobId = form.data('job-id');
        var token = form.find('input[name="_token"]').val();

        $.ajax({
            url: '/agency/like/' + jobId,
            type: 'POST',
            data: {_token: token},
            success: function(response) {
                btn.find('.like-count').text(response.likes_count);
                if(response.liked){
                    btn.removeClass('btn-outline-primary').addClass('btn-primary');
                } else {
                    btn.removeClass('btn-primary').addClass('btn-outline-primary');
                }
            },
            error: function(xhr){
                alert('Something went wrong!');
            }
        });
    });
});
</script>
@stop
