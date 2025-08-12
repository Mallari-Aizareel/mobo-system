@extends('adminlte::page')

@section('title', 'Account Settings')

@section('content_header')
    <h1>Account Settings</h1>
@stop

@section('content')
<style>
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

</style>
<div class="container">
    <form action="{{ route('tesda.account.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mb-4">
            <div class="card-body p-0">
                <div class="profile-header">
                    <img src="{{ $user->background_picture 
                        ? asset('storage/' . $user->background_picture) 
                        : asset('storage/background_pictures/default.jpg') }}" 
                        class="profile-bg" alt="Background Image">

                        <input type="file" name="background_picture" accept="image/*" hidden id="background_picture_input">

                        <div class="bg-upload-wrapper">
                            <label for="background_picture_input" class="bg-camera-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>

                    <img src="{{ Auth::user()->profile_picture 
                        ? asset('storage/' . Auth::user()->profile_picture) 
                        : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->firstname.' '.Auth::user()->lastname) }}" 
                    alt="Profile" class="profile-picture img-circle elevation-1">

                    <div class="change-picture-btn">
                        <div class="input-group input-group-sm">
                            <input type="file" name="profile_picture" accept="image/*" hidden id="profile_picture_input">
                                <input type="text" class="form-control form-control-sm" id="file_name_display" placeholder="Update profile picture" readonly>
                            <label for="profile_picture_input" class="btn btn-sm btn-secondary mb-0">
                                <i class="fas fa-camera"></i> Browse
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    
        <div style="margin-top: 120px;">
                <div class="card">
                    <div class="card-header"><strong>Personal Information</strong></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>First Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname', Auth::user()->firstname) }}" required>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Middle Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="middlename" class="form-control" value="{{ old('middlename', Auth::user()->middlename) }}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Last Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname', Auth::user()->lastname) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Gender</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                    </div>
                                    <select name="gender_id" class="form-control">
                                        <option value="">Select Gender</option>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender->id }}" {{ Auth::user()->gender_id == $gender->id ? 'selected' : '' }}>{{ $gender->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Date of Birth</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', Auth::user()->birthdate) }}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Religion</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-praying-hands"></i></span>
                                    </div>
                                    <input type="text" name="religion" class="form-control" value="{{ old('religion', Auth::user()->religion) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header"><strong>Contact Information</strong></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Contact Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                    </div>
                                    <input type="text" name="phone_number" class="form-control"
                                           value="{{ old('phone_number', Auth::user()->phone_number) }}"
                                           minlength="11" maxlength="11" pattern="\d{11}" placeholder="e.g. 09xxxxxxxxx">

                                           
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Email Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header"><strong>Address</strong></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Street</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-road"></i></span>
                                    </div>
                                    <input type="text" name="street" class="form-control" value="{{ old('street', optional(Auth::user()->address)->street) }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Barangay</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" name="barangay" class="form-control" value="{{ old('barangay', optional(Auth::user()->address)->barangay) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', optional(Auth::user()->address)->city) }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Province</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                                    </div>
                                    <input type="text" name="province" class="form-control" value="{{ old('province', optional(Auth::user()->address)->province) }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                    </div>
                                    <input type="text" name="country" class="form-control" value="{{ old('country', optional(Auth::user()->address)->country) }}">
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="card-header"><strong>Description</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>About You</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                </div>
                                <textarea name="description" rows="1" class="form-control" placeholder="Say something about yourself...">{{ old('description', Auth::user()->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-header"><strong>My Skills</strong></div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addSkillModal">
                            <i class="fas fa-plus-circle"></i> Add Skill
                        </button>

                        <ul class="list-group">
                            @foreach ($skills as $skill)
                                <li class="list-group-item" id="skill-item-{{ $skill->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-5">
                                            <span id="skill-card-name-{{ $skill->id }}" class="font-weight-bold">{{ $skill->name }}</span>
                                        </div>

                                        <div class="col-4 text-center">
                                            <span class="badge badge-primary" id="skill-card-percentage-{{ $skill->id }}">{{ $skill->percentage }}%</span>
                                        </div>

                                        <div class="col-3 text-right">
                                            <a href="#" data-toggle="modal" data-target="#editSkillModal{{ $skill->id }}" class="mr-2">
                                                <i class="fas fa-edit text-warning"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteSkill({{ $skill->id }})">
    <i class="fas fa-trash-alt text-danger"></i>
</a>

                                        </div>
                                    </div>
                                </li>
                                
                                <div class="modal fade" id="editSkillModal{{ $skill->id }}" tabindex="-1" role="dialog" aria-labelledby="editSkillModalLabel{{ $skill->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Skill</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Skill Name</label>   
                                                    <input type="text" class="form-control edit-skill-name" data-id="{{ $skill->id }}" value="{{ $skill->name }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Skill Level (%)</label>
                                                    <input type="number" min="1" max="100" class="form-control edit-skill-percentage" data-id="{{ $skill->id }}" value="{{ $skill->percentage }}">
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary save-skill-edit" data-id="{{ $skill->id }}" data-dismiss="modal">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>

                    <div id="skills-container">
                        @foreach ($skills as $skill)
                            <input type="hidden" name="skills[{{ $skill->id }}][id]" value="{{ $skill->id }}">
                            <input type="hidden" name="skills[{{ $skill->id }}][name]" id="skill-name-{{ $skill->id }}" value="{{ $skill->name }}">
                            <input type="hidden" name="skills[{{ $skill->id }}][percentage]" id="skill-percentage-{{ $skill->id }}" value="{{ $skill->percentage }}">
                        @endforeach
                    </div>

                    <div id="deleted-skills"></div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Profile
                        </button>
                    </div>
                </div>  
        </div>
    </form>
</div>

<div class="modal fade" id="addSkillModal" tabindex="-1" role="dialog" aria-labelledby="addSkillModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="skillForm" onsubmit="addSkill(event)">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Skill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Skill Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Carpentry" required>
                </div>
                <div class="form-group">
                    <label>Skill Level (%)</label>
                    <input type="number" name="percentage" class="form-control" min="1" max="100" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Skill</button>
            </div>
        </div>
    </form>
  </div>
</div>
@stop

@section('js')

<script>
document.getElementById('profile_picture_input').addEventListener('change', function(event) {
    const fileName = event.target.files[0]?.name || "Update profile photo";
    document.getElementById('file_name_display').value = fileName;
});

document.getElementById('background_picture_input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-bg').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<script>
    const skills = [];
    function addSkill(event) {
        event.preventDefault();

        const name = document.querySelector('#addSkillModal input[name="name"]').value.trim();
        const percentage = document.querySelector('#addSkillModal input[name="percentage"]').value.trim();

        if (!name || !percentage || percentage < 1 || percentage > 100) {
            alert('Please enter a valid skill and percentage (1–100).');
            return;
        }

        const ul = document.querySelector('.list-group');
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `${name} <span class="badge badge-primary badge-pill">${percentage}%</span>`;

        ul.appendChild(li);

        const container = document.getElementById('skills-container');
        const index = container.childElementCount / 2; 

        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = `skills[${index}][name]`;
        nameInput.value = name;

        const percentageInput = document.createElement('input');
        percentageInput.type = 'hidden';
        percentageInput.name = `skills[${index}][percentage]`;
        percentageInput.value = percentage;

        container.appendChild(nameInput);
        container.appendChild(percentageInput);

        document.querySelector('#addSkillModal input[name="name"]').value = '';
        document.querySelector('#addSkillModal input[name="percentage"]').value = '';

        $('#addSkillModal').modal('hide');
    }

    function deleteSkill(skillId) {
    // Remove the skill visually
    document.getElementById(`skill-item-${skillId}`).remove();

    // Add to deleted-skills div
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'deleted_skills[]';
    input.value = skillId;
    input.id = `deleted-skill-${skillId}`;

    document.getElementById('deleted-skills').appendChild(input);
}

</script>
<script>
    document.querySelectorAll('.save-skill-edit').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nameInput = document.querySelector(`.edit-skill-name[data-id='${id}']`);
            const percentageInput = document.querySelector(`.edit-skill-percentage[data-id='${id}']`);

            document.getElementById(`skill-name-${id}`).value = nameInput.value;
            document.getElementById(`skill-percentage-${id}`).value = percentageInput.value;

            document.querySelector(`#skill-card-name-${id}`).textContent = nameInput.value;
            document.querySelector(`#skill-card-percentage-${id}`).textContent = percentageInput.value + '%';

            const modal = bootstrap.Modal.getInstance(document.getElementById(`editSkillModal${id}`));
            modal.hide();
        });
    });

</script>

@stop

