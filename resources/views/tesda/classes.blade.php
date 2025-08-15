@extends('adminlte::page')

@section('title', 'Class Details')

@section('content_header')
    <h1>Class Details</h1>
@endsection

@section('content')
<div class="container mt-2">

    <a href="{{ route('tesda.dashboard') }}" class="btn btn-primary mb-4">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    @if($enrollment->room)
        {{-- Assigned Class --}}
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">{{ $enrollment->course->name }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Training Center:</strong> {{ $enrollment->room->trainingCenter->name }}</p>
                <p><strong>Room Name:</strong> {{ $enrollment->room->name }}</p>
                <p><strong>Course:</strong> {{ $enrollment->course->name }}</p>
                {{-- Add more details if needed --}}
            </div>
        </div>
    @else
        {{-- Pending Enrollment --}}
        <div class="alert alert-warning text-center shadow-sm">
            <h5 class="mb-2">Enrollment Pending</h5>
            <p>No room assigned yet. Your enrollment for <strong>{{ $enrollment->course->name }}</strong> is still pending and is being reviewed by the admin.</p>
        </div>
    @endif

</div>
@endsection
