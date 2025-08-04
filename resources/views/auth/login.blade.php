<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MOBO Skills Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <style>
        body {
            background-color: #064eb8ff; 
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-wrapper {
            display: flex;
            width: 800px;
            height: 350px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .login-left {
            flex: 1;
            background: white url('{{ asset('storage/logo/logo.png') }}') center center no-repeat;
            background-size: cover;
        }
        .login-right {
            flex: 1;
            background-color: #002b66;
            padding: 2rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: bold;
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
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-left"></div>

    <div class="login-right">

        <div class="login-box">
            <div class="card-header text-center">
                <a href="#" class="h3"><b>MOBO SKILLS ADMIN</b></a>
            </div>
                   <div class="card-body">
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text"><i class="fas fa-lock"></i></div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary px-5">Login</button>
            </div>
        </div>
    </form>
    <p class="mb-0 register-link">
          <a href="{{ route('register') }}" class="register-link">Sign up</a>
    </p>
</div>

               
            </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
