@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h4><i class="fas fa-bell"></i> Notifications</h4>

    {{-- Filter Dropdown --}}
    <div class="dropdown">
 <button class="btn btn-link text-muted p-0" type="button" id="notificationsFilter" data-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-ellipsis-h fa-lg"></i>
</button>
<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsFilter">
    <li><a class="dropdown-item" href="{{ route('agency.notifications') }}">All</a></li>

    <li><a class="dropdown-item" href="{{ route('agency.notifications', ['filter' => 'new']) }}">New</a></li>
    <li><a class="dropdown-item" href="{{ route('agency.notifications', ['filter' => 'yesterday']) }}">Yesterday</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="{{ route('agency.notifications', ['filter' => '1_week']) }}">Last 1 week</a></li>
    <li><a class="dropdown-item" href="{{ route('agency.notifications', ['filter' => '2_weeks']) }}">Last 2 weeks</a></li>
    <li><a class="dropdown-item" href="{{ route('agency.notifications', ['filter' => '1_month']) }}">Last 1 month</a></li>
</ul>

    </div>
</div>
@stop

@section('content')
<div class="container">

@forelse($notifications as $notif)
    <div class="d-flex align-items-center p-2 border rounded mb-2 bg-light">
        <i class="{{ $notif['icon'] }} fa-lg mr-2"></i>
        <div>
            {!! $notif['text'] !!}
        </div>
        <div class="ms-auto text-muted small">{{ $notif['created_at']->diffForHumans() }}</div>
    </div>
@empty
    <div class="alert alert-info">No notifications yet.</div>
@endforelse



</div>
@stop
