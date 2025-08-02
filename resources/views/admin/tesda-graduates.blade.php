@extends('adminlte::page')

@section('title', 'TESDA Graduates')

@section('content_header')
    <h1>TESDA Graduates</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="graduatesTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Course Taken</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($graduates as $grad)
                        <tr>
                            <td>
                                <img src="{{ $grad->user->profile_picture ? asset('storage/' . $grad->user->profile_picture) : asset('default-profile.png') }}" 
                                     alt="Profile" width="50" height="50" class="rounded-circle">
                            </td>
                            <td>{{ $grad->user->firstname }} {{ $grad->user->lastname }}</td>
                            <td>{{ $grad->user->email }}</td>
                            <td>{{ $grad->user->phone_number }}</td>
                            <td>
                                @if ($grad->user->address)
                                    {{ $grad->user->address->street }},
                                    {{ $grad->user->address->barangay }},
                                    {{ $grad->user->address->city }},
                                    {{ $grad->user->address->province }},
                                    {{ $grad->user->address->country }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $grad->course->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('#graduatesTable').DataTable();
        });
    </script>
@stop
