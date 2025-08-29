@extends('adminlte::page')

@section('title', 'Job Posts')

@section('content_header')
    <h4> </h4>
@stop

@section('content')
<div class="container">
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
                    <a href="{{ route('tesda.agency.show', $job->agency_id) }}">
                        {{ $job->agency->firstname ?? 'Unknown Agency' }}
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
            <form action="{{ route('tesda.mute', $job->agency_id) }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Mute this Agency</button>
            </form>

            <form action="{{ route('tesda.ignore', $job->agency_id) }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Ignore notifications from this Agency</button>
            </form>
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


<div class="d-flex align-items-center mb-2">
    <form class="d-inline like-form" data-job-id="{{ $job->id }}">
        @csrf
        <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center like-btn" style="gap: 0.3rem;">
            <i class="fas fa-thumbs-up"></i>
            <span class="like-count">{{ $job->likes->count() }}</span>
        </button>
    </form>
</div>

{{-- Comments Section --}}
<div class="mt-3">
    <form action="{{ route('agency.comment', $job->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Write a comment..." required>
            <button class="btn btn-primary">Post</button>
        </div>
    </form>

    <div class="mt-2">
        @php
            $recommendations = $job->recommendations->where('match_score', '>=', 30);
        @endphp

        @if($recommendations->count())
            @foreach ($recommendations as $rec)
                <div class="p-3 border rounded mb-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="mt-2">
                            <strong>{{ $rec->user->firstname ?? 'Unknown' }}</strong>
                            <br>
                            <!-- Button triggers modal -->
                            <button type="button" class="btn btn-link p-0 fw-semibold view-resume-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#resumeModal" 
                                    data-resume="{{ asset('storage/'.$rec->resume_path) }}?v={{ time() }}"
                                    data-name="{{ $rec->user->firstname ?? 'Unknown' }}">
                                View Resume
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">No recommendations with match score ≥ 30.</p>
        @endif
    </div>
</div>

<!-- Single Modal for all resumes -->
<div class="modal fade" id="resumeModal" tabindex="-1" aria-labelledby="resumeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resumeModalLabel">Resume</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <!-- Google Docs Viewer embedded PDF for mobile-friendly WebView -->
                <iframe id="resumeFrame" 
                        src="" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        style="border:0;">
                </iframe>
            </div>
        </div>
    </div>
</div>

<!-- JS to load the resume into iframe -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resumeModal = document.getElementById('resumeModal');
        const resumeFrame = document.getElementById('resumeFrame');
        const resumeModalLabel = document.getElementById('resumeModalLabel');

        resumeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const resumeUrl = button.getAttribute('data-resume') + '&v=' + new Date().getTime(); // cache-busting
            const userName = button.getAttribute('data-name');
            resumeFrame.src = resumeUrl;
            resumeModalLabel.textContent = `Resume - ${userName}`;
        });

        resumeModal.addEventListener('hidden.bs.modal', function () {
            resumeFrame.src = ''; // clear iframe when modal closes
        });
    });
</script>


    {{-- Show comments --}}
    <div class="mt-2">
        @foreach($job->comments as $comment)
            <div class="border p-2 mb-1 rounded">
                <strong>{{ $comment->user->firstname }}</strong>: {{ $comment->content }}
                <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
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
                      {{ $job->agency->firstname ?? 'Unknown Agency' }}
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
<script>
$(document).ready(function() {
    $('.like-btn').click(function(e) {
        e.preventDefault(); 
        var btn = $(this);
        var form = btn.closest('.like-form');
        var jobId = form.data('job-id');
        var token = form.find('input[name="_token"]').val();

        $.ajax({
            url: '/agency/like/' + jobId, // Reuse agency route
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
