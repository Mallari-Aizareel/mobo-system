@extends('adminlte::page')

@section('title', 'Registered Agencies')

@section('content_header')
    <h1>Registered Agencies</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="agenciesTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>Agency Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Representative Name</th>
                        <th>Representative Phone</th>
                        <th>Representative Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agencies as $agency)
                        <tr>
                            <td>
                                <img src="{{ $agency->profile_picture ? asset('storage/' . $agency->profile_picture) : asset('default-profile.png') }}" 
                                     alt="Profile Picture" width="50" height="50" class="rounded-circle">
                            </td>
                            <td>{{ $agency->firstname }}</td>
                            <td>{{ $agency->email }}</td>
                            <td>{{ $agency->phone_number }}</td>
                            <td>
                                {{ $agency->agencyRepresentative?->first_name ?? 'N/A' }}
                                {{ $agency->agencyRepresentative?->last_name ?? '' }}
                            </td>
                            <td>{{ $agency->agencyRepresentative?->phone_number ?? 'N/A' }}</td>
                            <td>{{ $agency->agencyRepresentative?->email ?? 'N/A' }}</td>
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
            $('#agenciesTable').DataTable();
        });
    </script>
@stop
