@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
@stop

@section('content')
<style>
    /* General card styling */
    .card {
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    /* Profile header */
    .profile-header {
        position: relative;
        text-align: center;
    }
    .profile-bg {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .profile-picture {
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid #fff;
        border-radius: 50%;
        width: 130px;
        height: 130px;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    /* Profile info */
    .profile-info {
        margin-top: 80px;
        text-align: center;
    }
    .profile-info h3 {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .profile-info small {
        color: #6c757d;
    }

    /* Details */
    .details-section {
        margin-top: 25px;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
    }
    .detail-item {
        display: flex;
        align-items: center;
        margin: 12px 0;
        font-size: 15px;
    }
    .detail-item i {
        margin-right: 10px;
        color: #007bff;
    }

    /* Ratings */
    .ratings strong {
        display: block;
        margin-bottom: 6px;
    }
    .ratings i {
        font-size: 18px;
    }

    /* Likes */
    .like-section {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
        font-size: 15px;
    }
    .like-section button {
        border: none;
        background: none;
        cursor: pointer;
    }
    .like-section i {
        font-size: 20px;
    }

    /* About section */
    .about-section {
        margin-top: 25px;
        padding: 20px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #eee;
    }
    .about-section h5 {
        margin-bottom: 10px;
        color: #007bff;
    }

    /* Gallery */
    .gallery {
        margin-top: 25px;
    }
    .gallery h5 {
        margin-bottom: 15px;
        color: #007bff;
    }
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, 140px); /* fixed width boxes */
    gap: 12px;
    justify-content: start; /* left-align items */
}

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        cursor: pointer;
    }
.gallery-item img {
    width: 140px; /* match the column width */
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

    .gallery-item:hover img {
        transform: scale(1.08);
    }
    .gallery-item.more::after {
        content: attr(data-extra);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
    }

    /* Modal */
    .modal-content {
        border-radius: 10px;
    }
    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: 0 auto;
        border-radius: 10px;
    }
    .modal-navigation {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2rem;
        color: #fff;
        cursor: pointer;
        z-index: 1055;
        user-select: none;
        padding: 0 15px;
    }
    .modal-prev { left: 0; }
    .modal-next { right: 0; }
</style>

<div class="card">
    <div class="card-body">
        {{-- Profile Header --}}
        <div class="profile-header">
            <img src="{{ $agency->background_picture 
                ? asset('storage/' . $agency->background_picture) 
                : asset('storage/background_pictures/default.jpg') }}" 
                class="profile-bg" alt="Background Image">

            <img src="{{ $agency->profile_picture 
                ? asset('storage/' . $agency->profile_picture) 
                : 'https://ui-avatars.com/api/?name='.urlencode($agency->name) }}" 
                alt="Profile" class="profile-picture">
        </div>

        {{-- Info --}}
        <div class="profile-info">
            <h3>{{ $agency->firstname }}</h3>
            <small>{{ $agency->email }}</small>
        </div>

        {{-- Details Section --}}
        <div class="details-section">
            <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>
                    @if($agency->address)
                        {{ $agency->address->street }},
                        {{ $agency->address->barangay }},
                        {{ $agency->address->city }},
                        {{ $agency->address->province }},
                        {{ $agency->address->country }}
                    @else
                        No address provided
                    @endif
                </span>
            </div>

            <div class="detail-item">
                <i class="fas fa-phone-alt"></i>
                <span>{{ $agency->phone_number ?? 'No phone number' }}</span>
            </div>

            <div class="detail-item">
                <i class="fas fa-envelope"></i>
                <span>{{ $agency->email ?? 'No email' }}</span>
            </div>

{{-- Ratings --}}
<div class="detail-item ratings">
    <strong>Ratings:</strong>
    <span class="ml-2">({{ $agency->average_rating }}/5)</span>
</div>

{{-- My Rating (for non-agency users) --}}
@if(!auth()->user()->hasRole('agency'))
<div class="detail-item ratings">
    <strong>My Rating:</strong>
    <form method="POST" action="{{ route('agency.rate', $agency->id) }}" class="d-inline">
        @csrf
        @php $myRating = $agency->myRating(auth()->id()); @endphp
        @for($i=1; $i<=5; $i++)
            <button type="submit" name="rating" value="{{ $i }}" style="background:none;border:none;">
                @if($myRating && $i <= $myRating)
                    <i class="fas fa-star text-primary"></i>
                @else
                    <i class="far fa-star text-primary"></i>
                @endif
            </button>
        @endfor
    </form>
    @if(!$myRating)
        <span class="text-muted ml-2">(Not rated yet)</span>
    @endif
</div>
@endif

{{-- Likes --}}
<div class="detail-item like-section">
    @if(auth()->user()->hasRole('agency'))
        {{-- Agency role: use the agency route --}}
        <form method="POST" action="{{ route('agency.agency.like', $agency->id) }}" class="d-inline">
            @csrf
            <button type="submit">
                @if($agency->isLikedByUser(auth()->id()))
                    <i class="fas fa-thumbs-up text-primary"></i>
                @else
                    <i class="far fa-thumbs-up"></i>
                @endif
            </button>
        </form>
        <span>{{ $agency->likes_count }} Likes</span>
    @else
        {{-- Tesda (or other) users: use the tesda route --}}
        <form method="POST" action="{{ route('tesda.agency.like', $agency->id) }}" class="d-inline">
            @csrf
            <button type="submit">
                @if($agency->isLikedByUser(auth()->id()))
                    <i class="fas fa-thumbs-up text-primary"></i>
                @else
                    <i class="far fa-thumbs-up"></i>
                @endif
            </button>
        </form>
        <span>{{ $agency->likes_count }} Likes</span>
    @endif
</div>




        {{-- About --}}
        <div class="about-section">
            <h5><i class="fas fa-info-circle"></i> About Us</h5>
            <p>{{ $agency->description ?? 'No description provided.' }}</p>
        </div>

        {{-- Gallery --}}
        <div class="gallery">
            <h5><i class="fas fa-image"></i> Gallery</h5>
            <div class="gallery-grid">
                @php
                    $images = $agency->jobPosts ? $agency->jobPosts->whereNotNull('job_image') : collect();
                    $firstFour = $images->take(4);
                    $extra = $images->count() - 4;
                @endphp

                @foreach($firstFour as $image)
                    <div class="gallery-item" data-image="{{ $image->job_image_url }}">
                        <img src="{{ $image->job_image_url }}" alt="Job Post Image">
                    </div>
                @endforeach

                @if($extra > 0)
                    <div class="gallery-item more" data-image="{{ $firstFour->last()->job_image_url }}" data-extra="+{{ $extra }} more">
                        <img src="{{ $firstFour->last()->job_image_url }}" alt="More Images">
                    </div>
                @endif
            </div>
        </div>
    </div>

    
</div>
</div>
@if(auth()->user()->id == $agency->id)
    <h5 class="ml-2 mt-4 mb-3 text-primary"><i class="fas fa-briefcase"></i> My Posts</h5>
@else
    <h5 class="ml-2 mt-4 mb-3 text-primary"><i class="fas fa-briefcase"></i> Posts</h5>
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
                            <span class="fw-bold text-primary mb-0 d-block">
                                {{ $job->agency->name ?? 'Unknown Agency' }}
                            </span>
                            <small class="text-muted fst-italic">{{ $job->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    {{-- Ellipsis Dropdown with Edit/Delete --}}
             
                    <div class="dropdown">
    <button class="btn btn-link text-muted p-0" type="button" id="optionsMenu{{ $job->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="optionsMenu{{ $job->id }}">
        @if(auth()->user()->id == $agency->id)
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editJobModal{{ $job->id }}">Edit Post</a>
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
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Job Title</label>
                                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $job->job_position) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Job Location</label>
                                    <input type="text" name="job_location" class="form-control" value="{{ old('job_location', $job->job_location) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Salary</label>
                                    <input type="number" name="job_salary" class="form-control" value="{{ old('job_salary', $job->job_salary) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Upload Image (optional)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewEditImage(event, {{ $job->id }})">
                                    <div class="mt-2">
                                        <img id="editImagePreview{{ $job->id }}" src="{{ $job->job_image ? asset('storage/' . $job->job_image) : '#' }}" 
                                            alt="Image Preview" 
                                            style="max-width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); {{ $job->job_image ? '' : 'display:none;' }}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Job Description</label>
                                    <textarea name="job_description" class="form-control" rows="4" required>{{ old('job_description', $job->job_description) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Qualifications</label>
                                    <textarea name="job_qualifications" class="form-control" rows="3">{{ old('job_qualifications', $job->job_qualifications) }}</textarea>
                                </div>

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
                                       

{{-- Modal Lightbox --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content bg-dark">
      <div class="modal-body position-relative text-center">
        <span class="modal-navigation modal-prev">&laquo;</span>
        <img id="modalImage" class="modal-img" src="" alt="Preview">
        <span class="modal-navigation modal-next">&raquo;</span>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        const modalImage = document.getElementById('modalImage');
        const prevBtn = document.querySelector('.modal-prev');
        const nextBtn = document.querySelector('.modal-next');

        let currentIndex = 0;
        let imageSources = [];

        galleryItems.forEach((item, index) => {
            const src = item.querySelector("img").getAttribute("src");
            imageSources.push(src);
            item.addEventListener('click', () => {
                currentIndex = index;
                modalImage.src = imageSources[currentIndex];
                modal.show();
            });
        });

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + imageSources.length) % imageSources.length;
            modalImage.src = imageSources[currentIndex];
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % imageSources.length;
            modalImage.src = imageSources[currentIndex];
        });
    });
</script>
@stop
