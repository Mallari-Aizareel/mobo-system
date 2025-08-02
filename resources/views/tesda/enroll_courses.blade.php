@extends('adminlte::page')

@section('title', 'Enroll in a Course')

@section('content_header')
    <h1>Enroll in a Course</h1>
@stop

@section('content')
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('tesda.enroll.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Enroll Heading --}}
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Enroll</h5>
        </div>
        <div class="card-body">

            {{-- Course Selection --}}
            <div class="form-group">
                <label for="course_id">Course</label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Upload Requirements --}}
            <div class="form-group">
                <label for="valid_id">Upload Valid ID</label>
                <input type="file" name="valid_id" class="form-control-file" accept=".pdf,.png,.jpg,.jpeg" required>
            </div>

            <div class="form-group">
                <label for="certificate">Upload Educational Certificate</label>
                <input type="file" name="certificate" class="form-control-file" accept=".pdf,.png,.jpg,.jpeg" required>
            </div>

            {{-- Personal Information --}}
            <hr>
            <h5>Personal Information</h5>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" 
                            value="{{ old('firstname', $user->firstname) }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Middle Name</label>
                    <input type="text" name="middlename" class="form-control" 
       value="{{ old('middlename', $user->middlename) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Last Name</label>
                    <input type="text" name="lastname" class="form-control" 
       value="{{ old('lastname', $user->lastname) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Gender</label>
 <select name="gender" class="form-control" required>
    <option value="" disabled>-- Select --</option>
    @foreach ($genders as $gender)
        <option value="{{ $gender->id }}" {{ $user->gender_id == $gender->id ? 'selected' : '' }}>
            {{ $gender->name }}
        </option>
    @endforeach
</select>

                </div>

                <div class="form-group col-md-4">
                    <label>Birthdate</label>
                   <input type="date" name="birthdate" class="form-control"
       value="{{ old('birthdate', $user->birthdate) }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label>Religion</label>
                   <input type="text" name="religion" class="form-control" 
       value="{{ old('religion', $user->religion) }}">

                </div>
            </div>

            {{-- Contact Info --}}
            <hr>
            <h5>Contact Information</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Phone Number</label>
                   <input type="text" name="phone" class="form-control" 
       value="{{ old('phone', $user->phone_number) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Email</label>
                   <input type="email" name="email" class="form-control" 
       value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            {{-- Address --}}
            <hr>
            <h5>Address</h5>
            <div class="form-group">
               <input type="text" name="street" class="form-control mb-2" 
       value="{{ old('street', $user->address?->street) }}" required>
                <input type="text" name="barangay" class="form-control mb-2" 
       value="{{ old('barangay', $user->address?->barangay) }}" required>
                <input type="text" name="city" class="form-control mb-2" 
       value="{{ old('city', $user->address?->city) }}" required>
                <input type="text" name="province" class="form-control mb-2" 
       value="{{ old('province', $user->address?->province) }}" required>
                <input type="text" name="country" class="form-control mb-2" 
       value="{{ old('country', $user->address?->country) }}" required>
            </div>

            {{-- Agreements --}}
            <hr>
            <h5>Agreements</h5>
            @foreach($agreements as $agreement)
                <div class="form-group">
                    <label>{{ $agreement->name }}</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="agreements[{{ $agreement->id }}]" value="yes" required>
                        <label class="form-check-label">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="agreements[{{ $agreement->id }}]" value="no" required>
                        <label class="form-check-label">No</label>
                    </div>
                </div>
            @endforeach

            {{-- Submit --}}
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">Submit Enrollment</button>
            </div>

        </div>
    </div>
</form>
@stop
