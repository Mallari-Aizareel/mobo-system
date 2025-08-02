@extends('adminlte::page')

@php($adminlte_menu = []) 
@section('title', 'TESDA Dashboard')

@section('content_header')
    <h1>Welcome TESDA User</h1>
@endsection

@section('content')
    <p>This is your TESDA-specific dashboard.</p>
@endsection

@section('adminlte-sidebar')
    <li class="nav-item">
        <a href="{{ route('tesda.home') }}" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Reports</p>
        </a>
    </li>
    {{-- Add more TESDA items --}}
@endsection
