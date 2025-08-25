@extends('adminlte::page')

@section('title', 'Inbox')

@section('content_header')
    <h4><i class="fas fa-inbox"></i> Inbox</h4>
@stop

@section('content')
<div class="row">
    <!-- Contact List -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Contacts
            </div>
            <ul class="list-group list-group-flush">
                @foreach($contacts as $contact)
                    <a href="{{ route('tesda.messages-index', ['user_id' => $contact->id]) }}" 
                       class="list-group-item list-group-item-action {{ ($selectedUserId == $contact->id) ? 'active' : '' }}">
                        {{ $contact->firstname }} <br>
                        <small class="text-muted">{{ ucfirst($contact->role_name) }}</small>
                    </a>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Chat Window -->
    <div class="col-md-9">
        @if($selectedUserId)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Chat with {{ $contacts->where('id', $selectedUserId)->first()->firstname ?? 'User' }}
                </div>
                <div class="card-body" id="chatBox" style="height: 400px; overflow-y: auto; background-color: #f9f9f9;">
                    @foreach($messages as $message)
                        @if($message->sender_id == Auth::id())
                            <!-- Sender Message (Right) -->
                            <div class="d-flex justify-content-end mb-3">
                                <div class="p-2 bg-primary text-white rounded" style="max-width: 70%;">
                                    <strong>You</strong><br>
                                    {{ $message->message }}
                                    <br>
                                    <small class="text-light">{{ $message->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @else
                            <!-- Receiver Message (Left) -->
                            <div class="d-flex justify-content-start mb-3">
                                <div class="p-2 bg-light border rounded" style="max-width: 70%;">
                                    <strong>{{ $contacts->where('id', $selectedUserId)->first()->firstname }}</strong><br>
                                    {{ $message->message }}
                                    <br>
                                    <small class="text-muted">{{ $message->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="card-footer">
                    <form id="messageForm" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedUserId }}">
                        <div class="input-group">
                            <input type="text" id="messageInput" name="message" class="form-control" placeholder="Type a message..." required>
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">Select a contact to start messaging.</div>
        @endif
    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Send message via AJAX
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('tesda.messages-store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#messageInput').val(''); // Clear input
                loadMessages(); // Refresh chat
            }
        });
    });

    // Auto-refresh messages
    function loadMessages() {
        $.ajax({
            url: "{{ route('tesda.messages-index', ['user_id' => $selectedUserId]) }}",
            method: 'GET',
            success: function(response) {
                var newChat = $(response).find('#chatBox').html();
                $('#chatBox').html(newChat);
                $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
            }
        });
    }

    // Scroll to bottom initially
    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);

    // Refresh every 3 seconds
    setInterval(loadMessages, 3000);
});
</script>
@endsection
