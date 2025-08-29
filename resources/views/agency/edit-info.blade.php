@extends('adminlte::page')

@section('title', 'Edit Agency Info')

@section('content_header')
    <h4></h4>
@stop

@section('content')
<style>
    body { background-color: #e6e6e6; }
    .profile-header {
        position: relative;
        text-align: center;
    }

    .profile-bg {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 0.2rem;
    }

    .profile-picture {
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid #fff;
        border-radius: 50%;
        width: 140px;
        height: 140px;
        object-fit: cover;
        z-index: 2;
    }

    .profile-info {
        margin-top: 80px;
        text-align: center;
    }

    .profile-info h3 {
        margin-bottom: 0;
    }

    .profile-info span {
        color: gray;
    }

    .change-picture-btn {
        position: absolute;
        bottom: -100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 3;
        max-width: 300px;
        width: 100%;
    }

    .bg-upload-wrapper {
        position: absolute;
        bottom: 10px;
        right: 10px;
        z-index: 2;
    }

    .bg-camera-btn {
        background: rgba(0,0,0,0.6);
        color: white;
        padding: 6px 10px;
        border-radius: 20px;
        cursor: pointer;
    }

    .agency-name {
        margin-top: 100px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
    }

    .section {
       
        padding-bottom: 15px;
        margin-bottom: 20px;
        background: white;
        padding: 15px;
        border-radius: 8px;
    }

    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .file-input-label {
        cursor: pointer;
        color: white;
        background: black;
        padding: 5px;
        border-radius: 5px;
        font-size: 14px;
    }
</style>

<div class="bg-white p-4 rounded shadow">
    <div class="container">
        <form action="{{ route('agency.update-info') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="profile-header">
                        <img 
                            src="{{ $user->background_picture 
                                ? asset('storage/' . $user->background_picture) 
                                : asset('storage/background_pictures/default.jpg') }}" 
                            class="profile-bg" 
                            alt="Background Image" 
                            id="backgroundPicturePreview"
                        >

                        <input type="file" name="background_picture" accept="image/*" hidden id="background_picture_input" onchange="previewImage(event, 'backgroundPicturePreview')">

                        <div class="bg-upload-wrapper">
                            <label for="background_picture_input" class="bg-camera-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>

                        <img 
                            src="{{ $user->profile_picture 
                                ? asset('storage/' . $user->profile_picture) 
                                : 'https://ui-avatars.com/api/?name='.urlencode($user->firstname.' '.$user->lastname) }}" 
                            alt="Profile" 
                            class="profile-picture img-circle elevation-1" 
                            id="profilePicturePreview"
                        >

                        <div class="change-picture-btn">
                            <div class="input-group input-group-sm">
                                <input type="file" name="profile_picture" accept="image/*" hidden id="profile_picture_input" onchange="previewImage(event, 'profilePicturePreview')">
                                <input type="text" class="form-control form-control-sm" id="file_name_display" placeholder="Update profile picture" readonly>
                                <label for="profile_picture_input" class="btn btn-sm btn-secondary mb-0">
                                    <i class="fas fa-camera"></i> Browse
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Agency Name --}}
            <div class="agency-name">
                {{ $user->firstname ?? 'AGENCY NAME' }}
            </div>

            @if(session('success'))
                <div class="alert alert-success text-center mt-3">{{ session('success') }}</div>
            @endif

            {{-- Agency Info --}}
            <div class="section">
                <div class="section-title"><i class="fas fa-info-circle"></i> Agency Info</div>

                <label>Agency Name</label>
                <input type="text" name="firstname" class="form-control mb-3" value="{{ old('firstname', $user->firstname) }}" required>

                <label>Contact No.</label>
                <input type="text" name="phone_number" class="form-control mb-3" value="{{ old('phone_number', $user->phone_number) }}" required>

                <label>Email Address</label>
                <input type="email" name="email" class="form-control mb-3" value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- Address --}}
            <div class="section">
                <div class="section-title"><i class="fas fa-map-marker-alt"></i> Address</div>

                <label>Street</label>
                <input type="text" name="street" class="form-control mb-3" value="{{ old('street', $user->address->street ?? '') }}">

                <label>Barangay</label>
                <input type="text" name="barangay" class="form-control mb-3" value="{{ old('barangay', $user->address->barangay ?? '') }}">

                <label>City</label>
                <input type="text" name="city" class="form-control mb-3" value="{{ old('city', $user->address->city ?? '') }}">

                <label>Province</label>
                <input type="text" name="province" class="form-control mb-3" value="{{ old('province', $user->address->province ?? '') }}">

                <label>Country</label>
                <input type="text" name="country" class="form-control mb-3" value="{{ old('country', $user->address->country ?? '') }}">
            </div>

            {{-- Agency Representative --}}
            <div class="section">
                <div class="section-title"><i class="fas fa-user-tie"></i> Agency Representative</div>

                @php
                    $rep = $representatives->first();
                @endphp

                <label>First Name</label>
                <input type="text" name="representative_first_name" class="form-control mb-3" value="{{ old('representative_first_name', $rep->first_name ?? '') }}" required>

                <label>Last Name</label>
                <input type="text" name="representative_last_name" class="form-control mb-3" value="{{ old('representative_last_name', $rep->last_name ?? '') }}" required>

                <label>Phone Number</label>
                <input type="text" name="representative_phone_number" class="form-control mb-3" value="{{ old('representative_phone_number', $rep->phone_number ?? '') }}" required>

                <label>Email Address</label>
                <input type="email" name="representative_email" class="form-control mb-3" value="{{ old('representative_email', $rep->email ?? '') }}" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<script>
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            if (preview.tagName.toLowerCase() === 'img') {
                preview.src = e.target.result;
            } else {
                preview.style.backgroundImage = `url(${e.target.result})`;
                preview.style.backgroundSize = 'cover';
                preview.style.backgroundPosition = 'center center';
                preview.style.backgroundRepeat = 'no-repeat';
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('profile_picture_input').addEventListener('change', function(e) {
    const fileNameDisplay = document.getElementById('file_name_display');
    if (this.files.length > 0) {
        fileNameDisplay.value = this.files[0].name;
    } else {
        fileNameDisplay.value = '';
    }
});
</script>

@endsection