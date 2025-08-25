<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TesdaMessageController extends Controller
{
    public function index(Request $request)
    {
        $selectedUserId = $request->input('user_id');

        // Fetch all possible contacts except the authenticated TESDA user
        $contacts = User::with('role')
            ->where('id', '!=', Auth::id())
            ->get()
            ->map(function ($user) {
                $user->role_name = $user->role ? $user->role->name : 'User';
                return $user;
            });

        // Fetch messages only if a user is selected
        $messages = [];
        if ($selectedUserId) {
            $messages = Message::where(function ($query) use ($selectedUserId) {
                    $query->where('sender_id', Auth::id())
                          ->where('receiver_id', $selectedUserId);
                })
                ->orWhere(function ($query) use ($selectedUserId) {
                    $query->where('sender_id', $selectedUserId)
                          ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('tesda.Inboxes', compact('contacts', 'selectedUserId', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->route('tesda.messages-index', ['user_id' => $request->receiver_id])
                         ->with('success', 'Message sent successfully!');
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        // Allow only sender to update
        if ($message->sender_id != Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate(['message' => 'required|string|max:1000']);
        $message->update(['message' => $request->message]);

        return back()->with('success', 'Message updated successfully.');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        // Allow only sender to delete
        if ($message->sender_id != Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $message->delete();

        return back()->with('success', 'Message deleted successfully.');
    }
}
