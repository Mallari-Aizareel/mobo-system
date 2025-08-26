@extends('adminlte::page')

@section('title', 'Admin Profile')

@section('content_header')
    <!-- <h1>My Profile</h1> -->
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
    }

    .about-section {
        margin-top: 30px;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
    }

    .contact-info {
        margin-top: 20px;
    }

    .contact-info p {
        margin: 5px 0;
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
<img src="{{ $user->background_picture
            ? asset('storage/' . $user->background_picture)
            : asset('storage/background_pictures/default.jpg') }}"
     class="profile-bg" alt="Background Image">



            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->firstname.' '.Auth::user()->lastname) }}" 
                alt="Profile" class="profile-picture img-circle elevation-1" width="40" height="40">
        </div>

        <div class="profile-info">
            <h3>{{ $user->firstname }} {{ $user->lastname }}</h3>
            <span>MOBO Skills Admin</span>

            <div class="contact-info">
    <p><i class="fas fa-map-marker-alt"></i> 
        @if ($user->address)
            {{ $user->address->street ?? '' }}, 
            {{ $user->address->barangay ?? '' }}, 
            {{ $user->address->city ?? '' }}, 
            {{ $user->address->province ?? '' }}, 
            {{ $user->address->country ?? '' }}
        @else
            No address available
        @endif
    </p>
    <p><i class="fas fa-phone-alt"></i> {{ $user->phone_number }}</p>
    <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
</div>

        </div>

        <div class="about-section">
            <h4>About Me</h4>
            <p>{{$user->description}}</p>
        </div>
    </div>
</div>
@stop
