@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <h4><i class="fas fa-user-circle"></i> MY PROFILE</h4>
@stop

@section('content')
<style>
    body { background-color: #e6e6e6; }
    .profile-header {
        position: relative;
        text-align: center;
    }
    .profile-bg {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 0.2rem;
    }
    .profile-picture {
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid #fff;
        border-radius: 50%;
        width: 140px;
        height: 140px;
        object-fit: cover;
        z-index: 2;
    }
    .agency-name {
        margin-top: 80px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
    }
    .agency-info {
        margin-top: 10px;
        text-align: center;
        font-size: 14px;
    }
    .info-icon {
        margin-right: 8px;
    }
    .section {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .gallery img {
        width: 100%;
        border-radius: 5px;
        object-fit: cover;
    }
    .post-card {
        background: white;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }
    .post-header {
        padding: 10px;
        font-weight: bold;
    }
    .post-image {
        width: 100%;
        object-fit: cover;
    }
    .post-footer {
        padding: 10px;
        display: flex;
        justify-content: space-between;
    }
</style>

<div class="bg-white p-4 rounded shadow">
    <div class="container">
        {{-- Header --}}
        <div class="card mb-4">
            <div class="card-body p-0">
                <div class="profile-header">
                    <img src="{{ $agency->background_picture 
                        ? asset('storage/' . $agency->background_picture) 
                        : asset('storage/background_picture/default.jpg') }}" 
                        class="profile-bg" alt="Background Image">

                    <img src="{{ $agency->profile_picture 
                        ? asset('storage/' . $agency->profile_picture) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($agency->name) }}" 
                        alt="Profile" class="profile-picture">
                </div>
            </div>
        </div>

        {{-- Agency Name --}}
        <div class="agency-name">
            {{ $agency->name }}
        </div>

        {{-- Agency Info --}}
        <div class="agency-info">
            <p><i class="fas fa-map-marker-alt info-icon"></i> {{ $agency->address }}</p>
            <p><i class="fas fa-phone info-icon"></i> {{ $agency->phone_number }}</p>
            <p><i class="fas fa-envelope info-icon"></i> {{ $agency->email }}</p>
        </div>

        {{-- About Us --}}
        <div class="section">
            <h5><i class="fas fa-info-circle"></i> About Us</h5>
            <p>{{ $agency->description ?? 'No description provided.' }}</p>
        </div>

        {{-- Gallery
        <div class="section">
            <h5><i class="fas fa-images"></i> Gallery</h5>
            <div class="row gallery">
                @foreach($gallery as $image)
                    <div class="col-3 mb-2">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Gallery Image">
                    </div>
                @endforeach
            </div>
        </div> --}}

        {{-- Posts
        <div class="section">
            <h5><i class="fas fa-edit"></i> Posts</h5>
            @foreach($posts as $post)
                <div class="post-card">
                    <div class="post-header">{{ $agency->name }}</div>
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="post-image" alt="Post Image">
                    @endif
                    <div class="p-3">
                        <p>{{ $post->content }}</p>
                    </div>
                    <div class="post-footer">
                        <span><i class="fas fa-thumbs-up"></i> {{ $post->likes_count }} like</span>
                        <span><i class="fas fa-comments"></i> See Comments</span>
                    </div>
                </div>
            @endforeach
        </div> --}}
    </div>
</div>
@endsection
