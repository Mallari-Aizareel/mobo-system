@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<style>
    .profile-bg {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 12px;
    }
    .profile-picture {
        position: absolute;
        bottom: -65px;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid #fff;
        border-radius: 50%;
        width: 140px;
        height: 140px;
        object-fit: cover;
        box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
    }
    .profile-name {
        margin-top: 80px;
        font-size: 26px;
        font-weight: bold;
        color: #343a40;
    }
    .section-title {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 15px;
        color: #495057;
        border-left: 4px solid #007bff;
        padding-left: 8px;
    }
    .card-custom ul {
        list-style: none;
        padding-left: 0;
    }

    .card-custom ul li {
        padding: 6px 0;
    }

    .card-custom ul li i {
        color: #007bff;
        margin-right: 8px;
    }

</style>

<div class="container-fluid">
    <div class="card mb-4">
        <div class="position-relative">
            <img src="{{ $user->background_picture ? asset('storage/' . $user->background_picture) : asset('storage/background_pictures/default.jpg') }}" 
                 class="profile-bg">
            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->firstname.' '.$user->lastname) }}" 
                 class="profile-picture">
        </div>

        <div class="card-body text-center">
            <div class="profile-name">
                {{ strtoupper($user->firstname) }} {{ strtoupper($user->lastname) }}
            </div>
            <div class="text-muted mb-3">TESDA Trainee</div>
        </div>
    </div>

    <div class="row">
        {{-- Contact Information --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="section-title"><i class="fas fa-address-card"></i> Contact Information</div>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 
                            @if($user->address)
                                {{ $user->address->street }}, 
                                {{ $user->address->barangay }}, 
                                {{ $user->address->city }}, 
                                {{ $user->address->province }}, 
                                {{ $user->address->country }}
                            @else
                                <span class="text-muted">No address provided</span>
                            @endif
                        </li>
                        <li><i class="fas fa-phone-alt"></i> {{ $user->phone_number ?? 'No phone provided' }}</li>
                        <li><i class="fas fa-envelope"></i> {{ $user->email }}</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- About --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="section-title"><i class="fas fa-info-circle"></i> About</div>
                    <p>{{ $user->description ?? 'No background information provided.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Certificates --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="section-title"><i class="fas fa-certificate"></i> Certificates</div>
                    <ul>
                        @forelse($user->completedCourses as $enrollment)
                            <li>
                                <i class="fas fa-award"></i> 
                                {{ $enrollment->course->name ?? 'Unnamed Course' }}
                                <span class="badge badge-success">Graduated</span>
                            </li>
                        @empty
                            <li><span class="text-muted">No certificates available</span></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Skills --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="section-title"><i class="fas fa-tools"></i> Skills</div>

                    @if($user->skills->isEmpty())
                        <p class="text-muted">No skills added</p>
                    @else
                        <div style="max-width: 100%; height: 300px;">
                            <canvas id="skillsChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(!$user->skills->isEmpty())
<script>
    const ctx = document.getElementById('skillsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($user->skills->pluck('name')),
            datasets: [{
                label: 'Skill Level',
                data: @json($user->skills->pluck('percentage')),
                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => context.raw + '%'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10,
                        callback: (value) => value + '%'
                    },
                    title: {
                        display: true,
                        text: 'Percentage (%)'
                    }
                }
            }
        }
    });
</script>
@endif
@stop
