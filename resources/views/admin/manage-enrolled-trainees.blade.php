@extends('adminlte::page')

@section('title', 'Manage Enrolled Trainees')

@section('content_header')
    <h1>Enrolled Trainees</h1>
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
                        <th>Date of Enrollment</th>
                        <th>Actions</th>
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
                           <td>{{ $trainee->created_at->format('F d, Y') }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.trainees.status', ['trainee' => $trainee->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_id" value="{{ $statusGraduated }}">
                                    <button type="button" class="btn btn-success btn-sm" title="Mark as Graduated" 
                                        onclick="openCertificateModal({{ $trainee->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>

                                </form>

                                <button type="button" class="btn btn-danger btn-sm" title="Mark as Failed" 
                                    onclick="openDropModal({{ $trainee->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="dropReasonModal" tabindex="-1" aria-labelledby="dropReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="dropReasonForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status_id" value="{{ $statusFailed }}">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="dropReasonModalLabel">Reason for Dropping Trainee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="form-group">
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="reason" class="form-control" rows="4" required></textarea>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Drop Trainee</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Example Bootstrap Modal -->
    <div class="modal fade" id="certificateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="certificateForm">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_id" value="{{ $statusGraduated }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">TESDA Certificate</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <div class="mb-3">
                            <label>NC Number</label>
                            <input type="text" name="nc_number" class="form-control" required>
                        </div> -->
                        <div class="mb-3">
                            <label>Issued Date</label>
                            <input type="date" name="issued_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Expiration Date</label>
                            <input type="date" name="expiration_date" class="form-control">
                            <small class="text-muted">Optional</small>
                        </div>
                        <div class="mb-3">
                            <label>Remarks</label>
                            <select name="remarks" class="form-control" required>
                                <option value="valid">Valid</option>
                                <option value="expired">Expired</option>
                                <option value="renewed">Renewed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Certificate</button>
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
            $('#traineesTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });

        function openDropModal(traineeId) {
            const form = document.getElementById('dropReasonForm');
            form.action = `/admin/trainees/${traineeId}/status`;

            const dropModal = new bootstrap.Modal(document.getElementById('dropReasonModal'));
            dropModal.show();
        }

        function openCertificateModal(traineeId) {
            const form = document.getElementById('certificateForm');
            form.action = `/admin/trainees/${traineeId}/status`; // same route as updateStatus
            const certModal = new bootstrap.Modal(document.getElementById('certificateModal'));
            certModal.show();
        }



    </script>
@stop
