<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portal Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
<style>
        body {
            background: url('{{ asset('storage/logo/logo.png') }}') no-repeat center center;
            background-size: cover;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.70); 
            z-index: 0;
        }

        .login-box {
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

        .login-card-body {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

        .create-account {
            margin-top: 15px;
            text-align: center;
        }

        .create-account a {
            color: #007bff;
            text-decoration: none;
        }

        .create-account a:hover {
            text-decoration: underline;
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('storage/logo/logo.png') }}') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.70);
            z-index: 1;
        }

        .loader-content {
            position: relative;
            text-align: center;
            z-index: 2;
        }

        .loader .logo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
        }

        .loading-text {
            margin-top: 15px;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        }
</style>

</head>
<body class="hold-transition login-page">

    <!-- <div class="loader" id="loader">
        <div class="loader-content">
            <img src="{{ asset('storage/logo/logo.png') }}" alt="Logo" class="logo">
        </div>
    </div> -->

    <div class="login-box" id="loginBox">
        <div class="login-logo">
            <img src="{{ asset('storage/logo/logo.png') }}" alt="Logo">
        </div>

        <div class="card">
            <div class="card-body login-card-body">

                <form method="POST" action="{{ route('portal.login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Username" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>

                <div class="create-account">
                    <p>Don't have an account? <a href="{{ route('portal.register') }}">Create an account</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                document.getElementById('loader').style.display = 'none';
                document.getElementById('loginBox').style.display = 'block';
            }, 3000);
        });
    </script> -->

</body>
</html>
