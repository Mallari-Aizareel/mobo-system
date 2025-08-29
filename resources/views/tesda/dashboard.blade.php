@extends('adminlte::page')

@section('title', 'TESDA Dashboard')

@section('content_header')
    <h1>My Rooms</h1>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Search Bar --}}
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search enrolled courses..." value="{{ request('search') }}">
            </div>
        </div>
    </div>
<!-- 
    <div class="row mb-3">
        <div class="col-12">
            <h4>Enrolled Courses</h4>
        </div>
    </div> -->

    {{-- Enrolled Courses Grid --}}
    <div class="row" id="coursesGrid">
        @include('tesda.partials.enrolled_courses_grid', ['enrolledCourses' => $enrolledCourses])
    </div>
</div>
@endsection

@section('js')
<script>
    const searchInput = document.querySelector('input[name="search"]');
    const coursesGrid = document.getElementById('coursesGrid');

    let timeout = null;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const query = searchInput.value;

            fetch(`{{ route('tesda.dashboard') }}?search=${query}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                coursesGrid.innerHTML = html;
            });
        }, 100);
    });
</script>
@endsection