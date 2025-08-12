@extends('adminlte::page')

@section('title', 'Room Info')

@section('content_header')
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.classes.list') }}" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="mb-0">Room Information</h1>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ $room->name }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Created At:</strong> {{ $room->created_at->format('F d, Y') }}</p>
                <p><strong>Course:</strong> {{ $room->course->name ?? 'N/A' }}</p>
                <p><strong>Training Center:</strong> {{ $room->trainingCenter->name ?? 'N/A' }}</p>
                <p><strong>Total Trainees:</strong> {{ $trainees->count() }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Trainees</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainees as $trainee)
                            @if($trainee && $trainee->user)
                                <tr>
                                    <td>{{ $trainee->user->firstname }} {{ $trainee->user->lastname }}</td>
                                    <td>{{ $trainee->course->name ?? 'N/A' }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="2">No trainees found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop