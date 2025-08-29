@extends('adminlte::page')

@section('title', 'List of Training Centers')

@section('content_header')
    <h1>List of Training Centers</h1>
@stop

@section('content')
    <div class="mb-3 d-flex justify-content-end">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addTrainingCenterModal">
            <i class="fas fa-plus"></i> Add Training Center
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    <div class="card">
        <div class="card-body">
            <table id="training-centers-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Center Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Representative</th>
                        <th>Rep Phone</th>
                        <th>Rep Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($centers as $center)
                        <tr>
                            <td>{{ $center->name }}</td>
                            <td>{{ $center->tc_email }}</td>
                            <td>{{ $center->tc_phone_number }}</td>
                            <td>{{ $center->address }}</td>
                            <td>{{ $center->representative }}</td>
                            <td>{{ $center->r_phone_number }}</td>
                            <td>{{ $center->r_email }}</td>
                            <td>
                                {{-- Edit Button --}}
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal-{{ $center->id }}">
                                    <i class="fas fa-edit"></i> 
                                </button>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.training-centers.destroy', $center->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this training center?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($centers as $center)
                        <div class="modal fade" id="editModal-{{ $center->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $center->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document"> {{-- Make it large to match the add modal --}}
                                <form action="{{ route('admin.training-centers.update', $center->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title text-white">Edit Training Center</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body row"> {{-- Row for grid layout --}}
                                            <div class="form-group col-md-6">
                                                <label for="name">Training Center Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $center->name }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="representative">Representative Name</label>
                                                <input type="text" class="form-control" name="representative" value="{{ $center->representative }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="address">Center Address</label>
                                                <input type="text" class="form-control" name="address" value="{{ $center->address }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="r_email">Representative Email</label>
                                                <input type="email" class="form-control" name="r_email" value="{{ $center->r_email }}" required>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="tc_phone_number">Center Phone Number</label>
                                                <input type="text" class="form-control" name="tc_phone_number" value="{{ $center->tc_phone_number }}" 
                                                    required pattern="\d{11}" minlength="11" maxlength="11" title="Enter number">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="r_phone_number">Representative Phone Number</label>
                                                <input type="text" class="form-control" name="r_phone_number" value="{{ $center->r_phone_number }}"
                                                    required pattern="\d{11}" minlength="11" maxlength="11" title="Enter number">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="tc_email">Center Email</label>
                                                <input type="email" class="form-control" name="tc_email" value="{{ $center->tc_email }}" required>
                                            </div>
                                            
                                            
                                            
                                            
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    {{-- AdminLTE Modal --}}
    <div class="modal fade" id="addTrainingCenterModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('admin.training-centers.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="addModalLabel">Add Training Center</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label for="name">Training Center Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="representative">Representative Name</label>
                            <input type="text" class="form-control" name="representative" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="address">Center Address</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="r_email">Representative Email</label>
                            <input type="email" class="form-control" name="r_email" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="tc_phone_number">Center Phone Number</label>
                            <input type="text" class="form-control" name="tc_phone_number" 
                                required pattern="\d{11}" minlength="11" maxlength="11"
                                title="Enter number">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="r_phone_number">Representative Phone Number</label>
                            <input type="text" class="form-control" name="r_phone_number" 
                                required pattern="\d{11}" minlength="11" maxlength="11"
                                title="Enter number">
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="tc_email">Center Email</label>
                            <input type="email" class="form-control" name="tc_email" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Training Center</button>
                    </div>
                </div>
            </form>
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
            $('#training-centers-table').DataTable({
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@stop
