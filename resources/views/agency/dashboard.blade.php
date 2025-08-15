@extends('adminlte::page')

@section('title', 'Agency Dashboard')

@section('content_header')
    <h1>TESDA Graduates</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="graduatesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Course Taken</th>
                        <th>Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graduates as $graduate)
                        <tr>
                            <td>{{ $graduate->user->firstname }} {{ $graduate->user->lastname }}</td>
                            <td>{{ $graduate->course->name ?? '-' }}</td>
                            <td>{{ $graduate->room && $graduate->room->course ? $graduate->room->course->name : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

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
