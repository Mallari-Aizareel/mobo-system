@extends('adminlte::page')

@section('title', 'Manage Classes')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Manage Classes</h1>
</div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.rooms.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="room_option"><strong>Room</strong></label>
                    <select name="room_option" id="room_option" class="form-control" onchange="toggleRoomInput()">
                        <option value="new">Create New Room</option>
                        @forelse($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @empty
                            <option disabled>No rooms yet</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group" id="room_name_group">
                    <label for="room_name"><strong>New Room Name</strong></label>
                    <input type="text" name="room_name" id="room_name" class="form-control" placeholder="Enter room name">
                </div>

                <div class="form-group">
                    <label for="enrolled_trainee_id"><strong>Trainee</strong></label>
                    <select name="trainee_id" id="enrolled_trainee_id" class="form-control" required>
                        <option value="">-- Select Trainee --</option>
                        @foreach($trainees as $trainee)
                            <option value="{{ $trainee->user_id }}" data-course="{{ $trainee->course_id }}">
                                {{ $trainee->user->firstname }} {{ $trainee->user->lastname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="course_id"><strong>Course</strong></label>
                    <select name="course_id" id="course_id" class="form-control">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="training_center_id"><strong>Training Center</strong></label>
                    <select name="training_center_id" id="training_center_id" class="form-control" required>
                        <option value="" disabled selected>Select Training Center</option>
                        @foreach($trainingCenters as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('enrolled_trainee_id').addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];
            let courseId = selected.getAttribute('data-course');
            let courseSelect = document.getElementById('course_id');
            courseSelect.value = courseId ? courseId : "";
        });

        function toggleRoomInput() {
            let select = document.getElementById('room_option');
            let roomNameGroup = document.getElementById('room_name_group');
            roomNameGroup.style.display = (select.value === 'new') ? 'block' : 'none';
        }
        toggleRoomInput();
    </script>
@stop