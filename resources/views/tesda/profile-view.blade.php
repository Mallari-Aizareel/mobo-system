@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <h4><i class="fas fa-user-circle"></i> MY PROFILE</h4>
@stop

@section('content')
<style>
    body {
        background-color: #e6e6e6;
    }
    .profile-bg {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    .profile-picture {
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid white;
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }
    .profile-name {
        margin-top: 80px;
        font-size: 26px;
        font-weight: bold;
    }
    .section {
        border-bottom: 2px solid black;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<div class="bg-white p-4 rounded shadow">
    <!-- Header with background -->
    <div class="position-relative">
        <img src="{{ $user->background_picture ? asset('storage/' . $user->background_picture) : asset('storage/background_pictures/default.jpg') }}" 
             class="profile-bg">
        <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->firstname.' '.$user->lastname) }}" 
             class="profile-picture">
    </div>

    <div class="text-center profile-name">
        {{ strtoupper($user->firstname) }} {{ strtoupper($user->lastname) }}
    </div>
    <div class="text-center text-muted mb-4">TESDA Trainee</div>

    <!-- Contact Info -->
    <div class="section">
        <p><i class="fas fa-map-marker-alt"></i> {{ $user->address ? $user->address->full_address : 'No address available' }}</p>
        <p><i class="fas fa-phone-alt"></i> {{ $user->phone_number }}</p>
        <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
    </div>

    <!-- About -->
    <div class="section">
        <div class="section-title"><i class="fas fa-info-circle"></i> About</div>
        <p>{{ $user->description ?? 'No background provided.' }}</p>
    </div>

    <!-- Certificates -->
    <div class="section">
        <div class="section-title"><i class="fas fa-certificate"></i> Certificates</div>
        {{-- <ul>
            @forelse($user->certificates as $cert)
                <li>{{ $cert->title }} - {{ $cert->year }}</li>
            @empty
                <li>No certificates available</li>
            @endforelse
        </ul> --}}
    </div>

<!-- Skills -->
<div class="section">
    <div class="section-title"><i class="fas fa-tools"></i> Skills</div>
    <div style="max-width: 500px; height: 250px; margin-left: 0;">
    {{-- <div style="max-width: 500px; height: 250px; margin: auto;"> --}}
        <canvas id="skillsChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('skillsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($user->skills->pluck('name')),
            datasets: [{
                label: 'Skill Level (%)',
                data: @json($user->skills->pluck('percentage')),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@stop
