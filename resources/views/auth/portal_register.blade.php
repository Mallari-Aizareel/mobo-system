<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portal Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body {
            position: relative;
            min-height: 100vh;
            background: url('{{ asset('storage/logo/logo.png') }}') no-repeat center center;
            background-size: cover;
            overflow: hidden;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }
        .register-box {
            position: relative;
            z-index: 1;
        }
        .login-logo img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            margin-bottom: 10px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .register-card-body {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
        }
        .login-link {
            margin-top: 15px;
            text-align: center;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="hold-transition login-page">

    <div class="register-box">
        <div class="login-logo">
            <img src="{{ asset('storage/logo/logo.png') }}" alt="Logo">
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Create your account</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('portal.register.step1') }}">
                    @csrf

                    <div class="mb-3">
                        <select name="role_id" class="form-control" required>
                            <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Select an option</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->id == 2 ? 'TESDA Trainee' : ucfirst($role->name) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Create Username" value="{{ old('username') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Create Password" autocomplete="off" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                        </div>
                    </div>
                </form>

                <div class="login-link">
                    <p>Already have an account? <a href="{{ route('portal.login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>