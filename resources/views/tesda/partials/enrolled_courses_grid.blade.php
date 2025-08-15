@forelse($enrolledCourses as $enrollment)
    <div class="col-lg-3 col-md-6 col-12 mb-3 d-flex align-items-stretch">
        @if($enrollment->room)
            {{-- Assigned Class --}}
            <div class="small-box bg-success w-100 d-flex flex-column justify-content-between">
                <div class="inner">
                    <h4>{{ $enrollment->room->course->name }}</h4>
                    <p>{{ $enrollment->room->trainingCenter->name }} - {{ $enrollment->room->name }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('tesda.class.show', $enrollment->id) }}" class="small-box-footer mt-auto">
                    View Class <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        @else
            {{-- Pending Enrollment --}}
            <div class="small-box bg-warning w-100 d-flex flex-column justify-content-between">
                <div class="inner">
                    <h4>{{ $enrollment->course->name }}</h4>
                    <p>Pending Assignment</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('tesda.class.show', $enrollment->id) }}" class="small-box-footer mt-auto">
                    View Class <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        @endif
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info">
            No classes found.
        </div>
    </div>
@endforelse
