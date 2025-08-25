@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
    <h1>Notifications</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @forelse($notifications as $notif)
            <div class="alert alert-{{ $notif->is_read ? 'secondary' : 'info' }}">
                <strong>{{ $notif->title }}</strong><br>
                {{ $notif->message }}
                <a href="{{ route('tesda.notifications-read', $notif->id) }}" class="btn btn-sm btn-primary float-right">
                    View
                </a>
            </div>
        @empty
            <p>No notifications yet.</p>
        @endforelse

        {{ $notifications->links() }}
    </div>
</div>
@stop
