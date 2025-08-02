<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Registration - Step 2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE & Bootstrap -->
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
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                
                <h3 class="mt-2 mb-0 text-black">Account Registration</h3>
            </div>
                @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

            <div class="card-body">
               <form method="POST" action="{{ route('portal.register.step2') }}">

    @csrf

    {{-- User Information Header --}}
    <h4 class="mb-3 font-weight-bold">User Information</h4>

    <div class="row">
        <div class="col-6">
            <x-adminlte-input name="first_name" label="First Name" placeholder="Enter first name" required />
        </div>
        <div class="col-6">
            <x-adminlte-input name="last_name" label="Last Name" placeholder="Enter last name" required />
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <x-adminlte-select name="gender_id" label="Gender" required>
                <option disabled selected>Select Gender</option>
                @foreach($genders as $gender)
                    <option value="{{ $gender->id }}">{{ ucfirst($gender->name) }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="col-6">
            <x-adminlte-input name="date_of_birth" label="Date of Birth" type="date" required />
        </div>
    </div>

    {{-- Contact Information Header --}}
    <h4 class="mt-4 mb-3 font-weight-bold">Contact Information</h4>

    <div class="row">
        <div class="col-6">
            <x-adminlte-input name="phone" label="Phone Number" placeholder="09XXXXXXXXX" required />
        </div>
        <div class="col-6">
            <x-adminlte-input name="email" type="email" label="Email" placeholder="example@email.com" required />
        </div>
    </div>

    {{-- Address Header --}}
    <h4 class="mt-4 mb-3 font-weight-bold">Address</h4>

    <div class="row">
        <div class="col-6">
            <x-adminlte-input name="street" label="Street" required />
        </div>
        <div class="col-6">
            <x-adminlte-input name="barangay" label="Barangay" required />
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <x-adminlte-input name="city" label="City" required />
        </div>
        <div class="col-6">
            <x-adminlte-input name="province" label="Province" required />
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <x-adminlte-input name="country" label="Country" required />
        </div>
    </div>

    <div class="text-center mt-4">
        <x-adminlte-button label="Next" type="submit" theme="success" class="btn-block"/>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

<!-- AdminLTE Scripts -->
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
