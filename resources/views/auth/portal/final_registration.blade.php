<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Registration - Step 2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        body {
             background-color: rgba(11, 4, 85, 1);
        }


        .bg-dim {
            background-color: rgba(0, 0, 0, 0.55);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .register-box {
            width: 100%;
            max-width: 600px;
        }
        .register-logo img {
            width: 120px;
        }
    </style>
</head>
<body>
<div class="bg-dim">
    <div class="register-box">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;">
        <h4 class="text-center font-weight-bold mb-4">Finish Account Setup</h4>

        <form method="POST" action="{{ route('portal.register.final.submit') }}" enctype="multipart/form-data">

            @csrf

            <div class="text-center mb-3">
                <label for="profile_picture" class="form-label font-weight-bold d-block mb-2">Set Profile Picture</label>
                <div class="rounded-circle border border-secondary mx-auto mb-2 position-relative" style="width: 150px; height: 150px; overflow: hidden;">
                    <img id="preview" src="{{ asset('images/default-profile.png') }}" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    <div id="placeholder-text" class="w-100 h-100 d-flex align-items-center justify-content-center text-white font-weight-bold" style="background-color: #6c757d;">
                        Profile
                    </div>
                </div>

                <x-adminlte-input-file name="profile_picture" igroup-size="sm" onchange="previewImage(event)" accept="image/*" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-primary text-white">
                            <i class="fas fa-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
            </div>

            <div class="text-muted text-center small mt-2 mb-4">
                The profile picture must be in formal attire. It serves as your resume profile photo.
            </div>

            <x-adminlte-button label="Register" type="submit" theme="success" class="btn-block"/>
        </form>
    </div>
</div>
    </div>
    </div>

<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();

        reader.onload = function(){
            const img = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder-text');

            img.src = reader.result;
            img.style.display = 'block';
            placeholder.style.display = 'none';
        };

        if (input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
