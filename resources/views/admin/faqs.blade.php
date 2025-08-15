@extends('adminlte::page')

@section('title', 'FAQs')

@section('content_header')
    <h1>FAQs Management</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><strong>Create FAQ</strong></div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                <form action="{{ route('admin.faqs.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="question">Question</label>
                        <input type="text" name="question" id="question" class="form-control" value="{{ old('question') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="answer">Answer</label>
                        <textarea name="answer" id="answer" class="form-control" rows="4" required>{{ old('answer') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Create FAQ</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><strong>FAQ List</strong></div>
            <div class="card-body">
                <table id="faqs-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Created At</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $faq)
                        <tr>
                            <td>{{ $faq->question }}</td>
                            <td>{{ $faq->answer }}</td>
                            <td>{{ $faq->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <button 
                                  class="btn btn-sm btn-info" 
                                  data-toggle="modal" 
                                  data-target="#editFaqModal" 
                                  data-id="{{ $faq->id }}" 
                                  data-question="{{ $faq->question }}" 
                                  data-answer="{{ $faq->answer }}">
                                  Edit
                                </button>

                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this FAQ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editFaqModal" tabindex="-1" aria-labelledby="editFaqModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editFaqForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editFaqModalLabel">Edit FAQ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="edit-question">Question</label>
            <input type="text" name="question" id="edit-question" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edit-answer">Answer</label>
            <textarea name="answer" id="edit-answer" class="form-control" rows="4" required></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update FAQ</button>
        </div>
      </div>
    </form>
  </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('#faqs-table').DataTable();

    $('#editFaqModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var faqId = button.data('id');
        var question = button.data('question');
        var answer = button.data('answer');

        var modal = $(this);
        modal.find('#edit-question').val(question);
        modal.find('#edit-answer').val(answer);

        var form = modal.find('#editFaqForm');
        var action = "{{ url('admin/faqs') }}/" + faqId;
        form.attr('action', action);
    });
});
</script>
@stop