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
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
    }
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        cursor: pointer;
    }
    .gallery-item img {
        width: 100%;
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
        {{-- Agency role: show total likes only with icon --}}
        <i class="fas fa-thumbs-up text-primary"></i>
        <span>{{ $agency->likes_count }} Likes</span>
    @else
        {{-- Other users: can like --}}
        <form method="POST" action="{{ route('agency.like', $agency->id) }}" class="d-inline">
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
