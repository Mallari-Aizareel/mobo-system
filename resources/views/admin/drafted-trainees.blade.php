@extends('adminlte::page')

@section('title', 'Drafted Trainees')

@section('content_header')
    <h1>Drafted Trainees</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="traineesTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Enrolled Course</th>
                        <th>Valid ID</th>
                        <th>Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainees as $trainee)
                        <tr>
                            <td>
                                <img src="{{ $trainee->user->profile_picture ? asset('storage/' . $trainee->user->profile_picture) : asset('default-profile.png') }}" 
                                     alt="Profile Picture" width="50" height="50" class="rounded-circle">
                            </td>
                            <td>{{ $trainee->user->firstname }} {{ $trainee->user->lastname }}</td>
                            <td>{{ $trainee->user->email }}</td>
                            <td>{{ $trainee->user->phone_number }}</td>
                            <td>
                                @if ($trainee->user->address)
                                    {{ $trainee->user->address->street }},
                                    {{ $trainee->user->address->barangay }},
                                    {{ $trainee->user->address->city }},
                                    {{ $trainee->user->address->province }},
                                    {{ $trainee->user->address->country }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $trainee->course->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $trainee->valid_id) }}" target="_blank">View</a>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $trainee->certificate) }}" target="_blank">View</a>
                            </td>
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
            $('#traineesTable').DataTable();
        });
    </script>
@stop
