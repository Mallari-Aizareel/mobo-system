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
            </div>
        </div>

        {{-- Classmates --}}
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Your Classmates</h5>
            </div>
            <div class="card-body">
                @if($classmates->isEmpty())
                    <p>No other trainees in your class yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Course</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classmates as $classmate)
                                    @if($classmate && $classmate->user)
                                        <tr>
                                            <td>{{ $classmate->user->firstname }} {{ $classmate->user->lastname }}</td>
                                            <td>{{ $classmate->course->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

{{-- Room Modules --}}
<div class="card shadow-sm mt-3">
    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Room Modules</h5>
        <button id="downloadSelected" class="btn btn-sm btn-primary">Download Selected</button>
    </div>
    <div class="card-body">
        @if($modules->isEmpty())
            <p>No modules uploaded yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Module</th>
                            <th>Uploaded</th>
                            <th>Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $module)
                            <tr>
                                <td>
                                    <input type="checkbox" name="module_ids[]" value="{{ $module->id }}">
                                </td>

                                <td>
                                    <strong>{{ basename($module->module_path) }}</strong><br>
                                    <a href="{{ route('tesda.modules.downloadSingle', $module->id) }}" class="btn btn-sm btn-primary mt-1">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>

                                <td>{{ $module->created_at->format('F d, Y') }}</td>

                                <td>
                                    @php
                                        $answer = $module->answers->where('user_id', auth()->id())->first();
                                    @endphp

                                    @if($answer)
                                        ✅ <strong>{{ basename($answer->answer_path) }}</strong><br>
                                        <small>Uploaded: {{ $answer->created_at->format('M d, Y') }}</small><br>
                                        <a href="{{ route('tesda.answers.download', $answer->id) }}" class="btn btn-sm btn-success mt-1">
                                            <i class="fas fa-download"></i> View Answer
                                        </a>
                                    @else
                                        <form action="{{ route('tesda.answers.store', $module->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="answer_file" class="form-control mb-2" required>
                                            <button type="submit" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-upload"></i> Upload
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
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

@section('js')
<script>
    document.getElementById('downloadSelected').addEventListener('click', function() {
        let form = document.getElementById('modulesForm');
        let checked = form.querySelectorAll('input[name="module_ids[]"]:checked');
        if(checked.length === 0) {
            alert('Please select at least one module to download.');
            return;
        }
        form.submit();
    });
</script>
@endsection

@endsection
