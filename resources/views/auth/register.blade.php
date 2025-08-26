<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MOBO Skills Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

    <style>
        body {
            position: relative;
            min-height: 100vh;
            background-color: #064eb8ff; 
            background-size: cover;
            overflow: hidden;
        }

        .register-wrapper {
            width: 600px;
            background-color: #002b66;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: white;
            position: relative;
            z-index: 1;
        }

        .form-control {
            background-color: #ccc;
            border: none;
        }

        .btn-primary {
            background-color: #0047b3;
            border: none;
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
            color: #E2DCF0;
        }

        .profile-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #fff;
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

        .placeholder-icon {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2rem;
        }

        #capture-btn {
            display: none;
            margin: 5px auto;
        }

        .instruction-text {
            font-size: 0.85rem;
            color: #ebb71eff;
            margin-bottom: 10px;
            text-align: center;
        }

    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; position: relative; z-index: 1;">
    <div class="register-wrapper">
        <div class="text-center mb-4">
            <h4><b>Create Your Account</b></h4>
        </div>

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="text-center mb-2">
                <div class="profile-circle" onclick="startCamera()">
                    <img id="previewImage" src="#" alt="Preview" style="display: none;">
                    <video id="video" autoplay playsinline style="display: none;"></video>
                    <div id="placeholder-icon" class="placeholder-icon">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="instruction-text">Click the circle above to take a photo or upload a profile picture.</div>

                <div class="input-group mb-2" style="max-width: 250px; margin: 0 auto;">
                    <div class="custom-file">
                        <input type="file" name="profile_picture" class="custom-file-input" id="profile_picture" accept="image/*">
                        <label class="custom-file-label text-dark" for="profile_picture">Upload Profile Picture</label>
                    </div>
                </div>

                <button type="button" class="btn btn-success" id="capture-btn" onclick="capturePhoto()">✅ Capture</button>
            </div>

            <div class="form-row mb-2">
                <div class="col">
                    <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col">
                    <input type="text" name="middlename" class="form-control" placeholder="Middle Name (Optional)">
                </div>
                <div class="col">
                    <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                </div>
            </div>

            <div class="input-group mb-2">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fas fa-id-badge"></i></div>
                </div>
            </div>

            <div class="input-group mb-2">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                </div>
            </div>

            <div class="input-group mb-2">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                </div>
            </div>

            <div class="input-group mb-2">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                </div>
            </div>

            <div class="text-center mb-3">
                <button type="submit" class="btn btn-primary px-5">Create</button>
            </div>
        </form>

        <p class="register-link">
            Already have an account? <a href="{{ route('login') }}" class="text-light"><strong>Login here</strong></a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    const video = document.getElementById('video');
    const previewImage = document.getElementById('previewImage');
    const placeholderIcon = document.getElementById('placeholder-icon');
    const captureBtn = document.getElementById('capture-btn');
    const fileInput = document.getElementById('profile_picture');
    let stream;

    function startCamera() {
        if (stream) return;
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(s => {
                stream = s;
                video.srcObject = stream;
                video.style.display = 'block';
                placeholderIcon.style.display = 'none';
                previewImage.style.display = 'none';
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

        stream.getTracks().forEach(track => track.stop());
        stream = null;

        previewImage.src = canvas.toDataURL('image/png');
        previewImage.style.display = 'block';
        video.style.display = 'none';
        captureBtn.style.display = 'none';
        placeholderIcon.style.display = 'none';

        canvas.toBlob(function(blob) {
            const file = new File([blob], 'profile_picture.png', { type: 'image/png' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }, 'image/png');
    }

    $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
    });

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                previewImage.src = evt.target.result;
                previewImage.style.display = 'block';
                placeholderIcon.style.display = 'none';
                video.style.display = 'none';
                captureBtn.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>
