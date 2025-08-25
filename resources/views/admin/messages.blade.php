@extends('adminlte::page')

@section('title', 'Messages')

@section('content_header')
    <h4><i class="fas fa-envelope"></i> Messages</h4>
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
                    <a href="{{ route('admin.messages-index', ['user_id' => $contact->id]) }}" 
                       class="list-group-item list-group-item-action {{ ($selectedUserId == $contact->id) ? 'active' : '' }}">
                        {{ $contact->firstname ?? 'User' }} <br>
                        <small class="text-muted">{{ ucfirst($contact->role_name ?? 'User') }}</small>
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
                    Chat with {{ $contacts->firstWhere('id', $selectedUserId)?->firstname ?? 'User' }}
                </div>
                <div id="chat-box" class="card-body" style="height: 400px; overflow-y: auto; background-color: #f9f9f9;">
                    @forelse($messages as $message)
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
                                    <strong>{{ $contacts->firstWhere('id', $selectedUserId)?->firstname ?? 'User' }}</strong><br>
                                    {{ $message->message }}
                                    <br>
                                    <small class="text-muted">{{ $message->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-muted">No messages yet. Select a contact to start messaging.</div>
                    @endforelse
                </div>
                <div class="card-footer">
                    <form id="message-form" action="{{ route('admin.messages-store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedUserId }}">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                            <button class="btn btn-primary" type="submit">Send</button>
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
<script>
$(document).ready(function () {
    $('#message-form').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        let url = $(this).attr('action');

        $.post(url, formData, function () {
            $('input[name="message"]').val('');
            loadMessages();
        });
    });

    function loadMessages() {
        let selectedUserId = "{{ $selectedUserId }}";
        if (!selectedUserId) return;

        $.get("{{ route('admin.messages-index') }}?user_id=" + selectedUserId, function (data) {
            let newContent = $(data).find('#chat-box').html();
            $('#chat-box').html(newContent);
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        });
    }

    setInterval(loadMessages, 5000); // Refresh every 5 seconds
});
</script>
@stop
