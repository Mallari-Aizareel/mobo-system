@extends('adminlte::page')

@section('title', 'Classes List')

@section('content_header')
    <h1>Classes</h1>
@stop

@section('content')
<div class="row">
    @foreach($rooms as $room)
        <div class="col-md-3 col-sm-6 col-12">
            <a href="{{ route('admin.rooms.show', $room->id) }}" style="text-decoration:none;">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-door-open"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Room</span>
                        <span class="info-box-number">{{ $room->name }}</span>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@stop