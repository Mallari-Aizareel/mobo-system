@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    {{-- Optional header, keep empty or add title --}}
@stop

@section('content')
<style>
    .profile-header {
        position: relative;
        text-align: center;
    }

    .profile-bg {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 0.5rem;
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

    .profile-info {
        margin-top: 80px;
        text-align: center;
    }

    .profile-info h3 {
        margin-bottom: 0;
    }

    .profile-info span {
        color: gray;
        font-weight: 600;
    }

    .contact-info {
        margin-top: 15px;
    }

    .contact-info p {
        margin: 5px 0;
        font-size: 14px;
    }

    .about-section {
        margin-top: 30px;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
        font-size: 15px;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card">
    <div class="card-body">
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

        <div class="profile-info">
            <h3>{{ $agency->name }}</h3>
            <span>Agency Profile</span>

            <div class="contact-info">
                <p><i class="fas fa-map-marker-alt"></i> <p><i class="fas fa-map-marker-alt"></i>
@if($agency->address)
    {{ $agency->address->street }}, 
    {{ $agency->address->barangay }}, 
    {{ $agency->address->city }}, 
    {{ $agency->address->province }}, 
    {{ $agency->address->country }}
@else
    No address provided
@endif
</p>
</p>
                <p><i class="fas fa-phone-alt"></i> {{ $agency->phone_number ?? 'No phone number' }}</p>
                <p><i class="fas fa-envelope"></i> {{ $agency->email ?? 'No email' }}</p>
            </div>
        </div>

        <div class="about-section">
            <h4>About Us</h4>
            <p>{{ $agency->description ?? 'No description provided.' }}</p>
        </div>
    </div>
</div>
@stop
