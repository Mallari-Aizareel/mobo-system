<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
<div class="sidebar d-flex flex-column h-100">

    {{-- Sidebar navigation --}}
    <nav class="pt-2">
        <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
            data-widget="treeview" role="menu"
            @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
            @endif
            @if(!config('adminlte.sidebar_nav_accordion'))
                data-accordion="false"
            @endif>
            {{-- Configured sidebar links --}}
            @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
        </ul>
    </nav>

    {{-- Flexible spacer --}}
    <div class="flex-grow-1"></div>

    {{-- Footer pushed far down --}}
<div class="sidebar-footer text-white border-top p-1">
    @if(Auth::user()->role_id == 1)
        <div class="text-uppercase text-muted medium mb-2 text-center sidebar-expanded-only font-weight-bold">
            ADMIN
        </div>
    @endif

    <div class="d-flex align-items-center mb-3">
        <img 
            src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('storage/logo/default-icon.jpg') }}" 
            alt="Profile" 
            class="img-circle elevation-1" 
            width="40" 
            height="40">

        <div class="ml-2 text-left sidebar-expanded-only">
            <div class="font-weight-bold">
                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
            </div>
        </div>
    </div>

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


</div>

</aside>
