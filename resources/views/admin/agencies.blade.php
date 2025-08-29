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
                        <th>Date of Registration</th> <!-- added -->
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
                                @forelse ($agency->agencyRepresentatives as $rep)
                                    {{ $rep->first_name }} {{ $rep->last_name }}<br>
                                @empty
                                    N/A
                                @endforelse
                            </td>

                            <td>
                                @forelse ($agency->agencyRepresentatives as $rep)
                                    {{ $rep->phone_number }}<br>
                                @empty
                                    N/A
                                @endforelse
                            </td>

                            <td>
                                @forelse ($agency->agencyRepresentatives as $rep)
                                    {{ $rep->email }}<br>
                                @empty
                                    N/A
                                @endforelse
                            </td>

                            <td>{{ $agency->created_at->format('F d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('#agenciesTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@stop
