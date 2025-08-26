<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agency Account Registration - Final Registration</title>
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
        .profile-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #6c757d;
            cursor: pointer;
            position: relative;
            margin: 0 auto 10px auto;
        }
        .profile-circle img,
        .profile-circle video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .placeholder-text {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            background-color: #6c757d;
        }
        .instruction-text {
            font-size: 0.85rem;
            color: #ebb71eff;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="bg-dim">
    <div class="register-box">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;">
                <h4 class="text-center font-weight-bold mb-4">Finish Account Setup</h4>

                <form method="POST" action="{{ route('portal.agency.register.final.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="text-center mb-4">
                        <label for="profile_picture" class="form-label font-weight-bold d-block mb-2">Set Profile Picture / Logo</label>

                        <div class="profile-circle" onclick="startCamera()">
                            <img id="preview" src="{{ asset('images/default-profile.png') }}" style="display: none;">
                            <video id="video" autoplay playsinline style="display: none;"></video>
                            <div id="placeholder-text" class="placeholder-text">Profile</div>
                        </div>

                        <div class="instruction-text">Click the circle above to take a photo of your agency or upload a file.</div>

                        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
                            <x-adminlte-input-file name="profile_picture" igroup-size="sm" onchange="previewImage(event)" accept="image/*">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-primary text-white">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-file>
                        </div>

                        <button type="button" class="btn btn-success mb-2" onclick="capturePhoto()" id="capture-btn" style="display:none;">✅ Capture</button>

                        <small class="text-muted d-block mt-1">
                            Required to upload photo/logo of your agency.
                        </small>
                    </div>

                    <x-adminlte-button label="Register" type="submit" theme="success" class="btn-block"/>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder-text');
    const captureBtn = document.getElementById('capture-btn');
    let stream;

    function startCamera() {
        if (stream) return;
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(s => {
                stream = s;
                video.srcObject = stream;
                video.style.display = 'block';
                placeholder.style.display = 'none';
                preview.style.display = 'none';
                captureBtn.style.display = 'inline-block';
            })
            .catch(err => {
                alert('Camera access denied or not available.');
                console.error(err);
            });
    }

    function capturePhoto() {
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Stop camera
        stream.getTracks().forEach(track => track.stop());
        stream = null;

        // Show preview
        preview.src = canvas.toDataURL('image/png');
        preview.style.display = 'block';
        video.style.display = 'none';
        captureBtn.style.display = 'none';
        placeholder.style.display = 'none';

        // Attach to profile_picture input
        canvas.toBlob(function(blob) {
            const file = new File([blob], 'profile_picture.png', { type: 'image/png' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            const fileInput = document.querySelector('input[name="profile_picture"]');
            fileInput.files = dataTransfer.files;
        }, 'image/png');
    }

    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            preview.src = reader.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            video.style.display = 'none';
            captureBtn.style.display = 'none';
        };
        if (input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
