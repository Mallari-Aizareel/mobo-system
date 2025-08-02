@extends('adminlte::page')

@section('title', 'Course Agreements')

@section('content_header')
    <h1>Course Agreements</h1>
@stop

@section('content')

    {{-- Add Agreement Button --}}
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addAgreementModal">
        <i class="fas fa-plus"></i> Add Course Agreement
    </button>

    {{-- DataTable --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="agreements-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Agreement Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agreements as $agreement)
                        <tr>
                            <td>{{ $agreement->id }}</td>
                            <td>{{ $agreement->name }}</td>
                            <td>{{ $agreement->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Agreement Modal --}}
    <div class="modal fade" id="addAgreementModal" tabindex="-1" role="dialog" aria-labelledby="addAgreementModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.agreements.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAgreementModalLabel">Add Course Agreement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="agreement-name">Agreement Question</label>
                            <input type="text" name="name" id="agreement-name" class="form-control" required placeholder="Enter agreement...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Agreement</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
