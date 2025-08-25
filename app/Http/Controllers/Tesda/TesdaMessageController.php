<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TesdaMessageController extends Controller
{
    /**
     * Display the inbox with contacts and messages
     */
    public function index(Request $request)
    {
        $selectedUserId = $request->input('user_id');
        $authId = Auth::id();

        // Get IDs of users the logged-in user has messages with
        $contactIds = Message::where('sender_id', $authId)
                        ->orWhere('receiver_id', $authId)
                        ->get(['sender_id', 'receiver_id'])
                        ->flatMap(function ($msg) use ($authId) {
                            return $msg->sender_id == $authId ? [$msg->receiver_id] : [$msg->sender_id];
                        })
                        ->unique()
                        ->toArray();

        // Include admin by default (assuming role_id = 1)
        $admin = User::where('role_id', 1)->first();
        if ($admin) {
            $contactIds[] = $admin->id;
        }

        // Fetch contacts
        $contacts = User::with('role')
            ->whereIn('id', $contactIds)
            ->get()
            ->map(function ($user) {
                $user->role_name = $user->role ? $user->role->name : 'User';
                return $user;
            });

        // Fetch messages with selected user
        $messages = [];
        if ($selectedUserId) {
            $messages = Message::where(function ($query) use ($selectedUserId, $authId) {
                    $query->where('sender_id', $authId)
                          ->where('receiver_id', $selectedUserId);
                })
                ->orWhere(function ($query) use ($selectedUserId, $authId) {
                    $query->where('sender_id', $selectedUserId)
                          ->where('receiver_id', $authId);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('tesda.Inboxes', compact('contacts', 'selectedUserId', 'messages'));
    }

    /**
     * Store a new message
     */
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

    /**
     * Update an existing message
     */
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

    /**
     * Delete a message
     */
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
