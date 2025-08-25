@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
@stop

@section('content')
<style>
    .profile-header {
        position: relative;
        text-align: center;
    }
    .profile-bg {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    .profile-picture {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid #fff;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
        z-index: 2;
    }
    .profile-info {
        margin-top: 70px;
        text-align: center;
    }
    .profile-info h3 {
        font-weight: bold;
    }

    /* Contact + details */
    .details-section {
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 15px;
    }
    .detail-item {
        display: flex;
        align-items: center;
        margin: 8px 0;
        font-size: 14px;
    }
    .detail-item i {
        margin-right: 8px;
        color: #333;
    }

    /* Ratings */
    .ratings {
        margin: 10px 0;
        font-size: 16px;
        color: #f4c150;
    }

    /* Likes */
    .like-section {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 10px 0;
    }
    .like-section button {
        border: none;
        background: none;
        cursor: pointer;
        color: #007bff;
    }

    /* About */
    .about-section {
        margin-top: 20px;
        padding: 15px;
        border-top: 1px solid #ccc;
        font-size: 14px;
    }

    /* Gallery */
    .gallery {
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 15px;
    }
    .gallery h5 {
        margin-bottom: 10px;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
    }
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
    }
    .gallery-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.2s;
    }
    .gallery-item:hover img {
        transform: scale(1.05);
    }

    /* Modal (Lightbox) */
    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: 0 auto;
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
    }
    .modal-prev {
        left: 10px;
    }
    .modal-next {
        right: 10px;
    }
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
                alt="Profile" class="profile-picture img-circle elevation-1">
        </div>

        {{-- Info --}}
        <div class="profile-info">
            <h3>{{ $agency->firstname }}</h3>
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

            <div class="detail-item ratings">
                @php $rating = $agency->rating ?? 0; @endphp
                @for($i=1; $i<=5; $i++)
                    @if($i <= $rating)
                        <i class="fas fa-star"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>

            <div class="detail-item like-section">
                <form method="POST" action="{{ route('agency.like', $agency->id) }}">
                    @csrf
                    <button type="submit"><i class="fas fa-thumbs-up"></i></button>
                </form>
                <span>{{ $agency->likes_count ?? 0 }}</span>
            </div>
        </div>

        {{-- About --}}
        <div class="about-section">
            <h5><i class="fas fa-info-circle"></i> About Us</h5>
            <p>{{ $agency->description ?? 'No description provided.' }}</p>
        </div>

<div class="gallery">
    <h5><i class="fas fa-image"></i> Gallery</h5>
    <div class="gallery-grid">
        @php
            $images = $agency->jobPosts ? $agency->jobPosts->whereNotNull('job_image') : collect();
            $firstFour = $images->take(4);
            $extra = $images->count() - 4;
        @endphp

        @foreach($firstFour as $image)
            <div class="gallery-item">
                <img src="{{ $image->job_image_url }}" alt="Job Post Image">
            </div>
        @endforeach

        @if($extra > 0)
            <div class="gallery-item more">
                <img src="{{ $firstFour->last()->job_image_url }}" alt="More Images">
                <div class="overlay">+{{ $extra }} more</div>
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

{{-- JS for lightbox --}}
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
            imageSources.push(item.getAttribute('data-image'));
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
