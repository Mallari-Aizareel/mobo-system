@php
    $user = auth()->user();
    $roleId = $user->role_id;

    $logout_url = match ($roleId) {
        2, 3 => route('portal.logout'),
        default => route('logout'),
    };

    $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout');

    if (config('adminlte.usermenu_profile_url', false)) {
        $profile_url = Auth::user()->adminlte_profile_url();
    }

    if (config('adminlte.use_route_url', false)) {
        $profile_url = $profile_url ? route($profile_url) : '';
        $logout_url = $logout_url ? route($logout_url) : '';
    } else {
        $profile_url = $profile_url ? url($profile_url) : '';
        $logout_url = $logout_url ? url($logout_url) : '';
    }

    $profileLabel = 'My Profile';
    $settingsLabel = 'Account Settings';
    $showResume = false;
    $resumeRoute = '#';

    switch ($roleId) {
        case 1: 
            $profileRoute = route('admin.profile.index');
            $settingsRoute = route('admin.account.edit');
            break;

        case 2: 
            $profileRoute = route('tesda.profile');
            $settingsRoute = route('tesda.account.settings');
            $resumeRoute = route('tesda.resume');
            $settingsLabel = 'Edit Info';
            $showResume = true;
            break;

        case 3: 
            $profileRoute = route('agency.profile');
            $settingsRoute = route('agency.edit-info');
            $settingsLabel = 'Edit Info';
            break;

        default:
            $profileRoute = '#';
            $settingsRoute = '#';
    }
@endphp

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
               <span @if(config('adminlte.usermenu_image')) class="d-none d-md-inline" @endif>
            <span class="d-none d-md-inline">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>

        </span>
    @if(config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}"
                 class="user-image img-circle elevation-2"
                 alt="{{ Auth::user()->name }}">
        @endif

    </a>

 {{-- User menu dropdown --}}
<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

    <li class="user-footer text-center">
        <a href="{{ $profileRoute }}" class="btn btn-default btn-flat d-block w-100 mb-1">
            <i class="fa fa-user text-primary"></i> {{ $profileLabel }}
        </a>

        <a href="{{ $settingsRoute }}" class="btn btn-default btn-flat d-block w-100 mb-1">
            <i class="fa fa-cog text-success"></i> {{ $settingsLabel }}
        </a>

        @if($showResume)
            <a href="{{ $resumeRoute }}" class="btn btn-default btn-flat d-block w-100 mb-1">
                <i class="fa fa-file-alt text-info"></i> Resume
            </a>
        @endif

        <a class="btn btn-default btn-flat d-block w-100"
           href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off text-danger"></i> Logout
        </a>

        <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
            @if(config('adminlte.logout_method'))
                {{ method_field(config('adminlte.logout_method')) }}
            @endif
            {{ csrf_field() }}
        </form>
    </li>
</ul>




</li>
