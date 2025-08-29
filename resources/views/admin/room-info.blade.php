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
    {{-- Left Column: Room Info + Modules --}}
    <div class="col-md-4">
        {{-- Room Info Card --}}
        <div class="card card-primary mb-3">
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

        {{-- Room Modules Card --}}
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Room Modules</h3>
            </div>
            <div class="card-body">
                {{-- Upload Form --}}
                <form action="{{ route('admin.rooms.modules.store', $room->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="file" name="module_file" class="form-control" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Upload Module</button>
                        </div>
                    </div>
                </form>

                {{-- Modules List --}}
                <ul class="list-group">
                    @foreach($room->modules as $module)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ basename($module->module_path) }}</strong><br>
                                <small>Uploaded: {{ $module->created_at->format('F d, Y') }}</small>
                            </div>
                            <form action="{{ route('admin.rooms.modules.destroy', [$room->id, $module->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this module?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Right Column: Trainees + Module Answers --}}
    <div class="col-md-8">
        {{-- Trainees Card --}}
        <div class="card card-info mb-3">
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

        {{-- Module Answers Card --}}
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Module Answers</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>User</th>
                            <th>File</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($room->modules as $module)
                            @foreach($module->answers as $answer)
                                <tr>
                                    <td>{{ basename($module->module_path) }}</td>
                                    <td>{{ $answer->user->firstname }} {{ $answer->user->lastname }}</td>
                                    <td>{{ basename($answer->answer_path) }}</td>
                                    <td>{{ $answer->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.answers.download', $answer->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
