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
            background-image: url('{{ asset('storage/logo/logo.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            margin: 0;

            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(1, 6, 28, 0.94); 
            z-index: -1;
        }
        .register-wrapper {
            width: 600px;
            background-color: #002b66;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: white;
            margin:40px;
            border-radius: 5px;
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
        .custom-file-label {
            background-color: #ccc;
        }

        #profilePreview {
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.4);
        }

    </style>
</head>
<body>
    <script>
    @if(session('registration_success'))
        console.log('Registration successful!');
    @endif

    @if($errors->any())
        console.error('Registration failed:', @json($errors->all()));
    @endif
</script>


<div class="register-wrapper">
    <div class="text-center mb-4">
        <h4><b>Create Your Account</b></h4>
    </div>

    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="text-center mb-2">
            <div id="profilePreview" class="rounded-circle mx-auto d-flex justify-content-center align-items-center"
                style="width: 100px; height: 100px; background-color: #ccc; overflow: hidden;">
                <i class="fas fa-user fa-4x text-white" id="defaultIcon"></i>
                <img id="previewImage" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>

        <div class="input-group mb-2" style="max-width: 250px; margin: 0 auto;">
            <div class="custom-file">
                <input type="file" name="profile_picture" class="custom-file-input" id="profile_picture">
                <label class="custom-file-label text-dark" for="profile_picture">Upload Profile Picture</label>
            </div>
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
    $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
    });

    $('#profile_picture').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                $('#previewImage').attr('src', e.target.result).show();
                $('#defaultIcon').hide();
            };

            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>