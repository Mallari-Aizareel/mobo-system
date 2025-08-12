@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><strong>Course Color Key</strong></div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        @foreach ($courseData->keys() as $index => $courseName)
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center" style="font-size: 0.85rem;">
                                    <span style="
                                        width: 16px;
                                        height: 16px;
                                        background-color: {{ $colors[$index] }};
                                        display: inline-block;
                                        margin-right: 8px;
                                        border: 1px solid #000;
                                        border-radius: 3px;
                                    "></span>
                                    <span style="word-break: break-word;">{{ $courseName }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><strong>Course Distribution</strong></div>
            <div class="card-body">
                <div style="max-width: 250px; margin: 0 auto;">
                    <canvas id="coursePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-4 col-12">
        <a href="{{ route('admin.tesda.graduates') }}">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $graduatesCount }}</h3>
                    <p>Graduates</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-4 col-12">
        <a href="{{ route('admin.trainees.manage') }}">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $traineesCount }}</h3>
                    <p>Current Trainees</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-4 col-12">
        <a href="{{ route('admin.agencies.index') }}">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $agenciesCount }}</h3>
                    <p>Agencies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('coursePieChart').getContext('2d');
    const courseChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($courseData->keys()) !!},
            datasets: [{
                data: {!! json_encode($courseData->values()) !!},
                backgroundColor: {!! json_encode($colors) !!},
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Course Breakdown'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const data = context.dataset.data;
                            const total = data.reduce((sum, val) => sum + val, 0);
                            const currentValue = context.raw;
                            const percentage = ((currentValue / total) * 100).toFixed(1);
                            return `${context.label}: ${percentage}%`;
                        }
                    }
                }
            }
        }
    });
</script>
@stop