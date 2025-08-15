<aside class="main-sidebar main-sidebar-custom-xl sidebar-dark-primary elevation-4">

    {{-- Brand Logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Scrollable sidebar --}}
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

    <div class="sidebar-custom d-flex flex-column justify-content-between p-2">

        {{-- Row 1: Admin label --}}
        @if(Auth::user()->role_id == 1)
        <div class="text-uppercase text-muted small mb-2 text-center sidebar-expanded-only font-weight-bold">
            ADMIN
        </div>
        @endif

        @if(Auth::user()->role_id == 2)
        <div class="text-uppercase text-muted small mb-2 text-center sidebar-expanded-only font-weight-bold">
            USER
        </div>
        @endif


        @if(Auth::user()->role_id == 3)
        <div class="text-uppercase text-muted small mb-2 text-center sidebar-expanded-only font-weight-bold">
            AGENCY
        </div>
        @endif

        {{-- Row 2: Profile (centered) --}}
        <div class="d-flex align-items-center justify-content-center mb-2">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('storage/logo/default-icon.jpg') }}" 
                alt="Profile" class="img-circle elevation-1" width="40" height="40">
            <div class="ml-2 text-left sidebar-expanded-only text-white">
                <div class="font-weight-bold">
                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </div>
            </div>
        </div>

        {{-- Row 3: Logout button --}}
        <a href="#" class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center"
        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="fa fa-power-off text-danger"></i>
            <span class="ml-2 sidebar-expanded-only">Logout</span>
        </a>

        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            @if(config('adminlte.logout_method'))
                {{ method_field(config('adminlte.logout_method')) }}
            @endif
        </form>

    </div>
</aside>
