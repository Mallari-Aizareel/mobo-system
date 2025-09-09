<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display the messages page.
     */
public function index(Request $request)
{
    $authId = Auth::id();
    $selectedUserId = $request->get('user_id');

    // 1️⃣ Get IDs of users the logged-in user has messages with
    $contactIds = Message::where('sender_id', $authId)
                    ->orWhere('receiver_id', $authId)
                    ->get(['sender_id', 'receiver_id'])
                    ->flatMap(function ($msg) use ($authId) {
                        return $msg->sender_id == $authId
                            ? [$msg->receiver_id]
                            : [$msg->sender_id];
                    })
                    ->unique()
                    ->toArray();

    // 2️⃣ Include all admins (role_id = 1)
    $adminIds = User::where('role_id', 1)->pluck('id')->toArray();

    // Merge and remove duplicates
    $contactIds = array_unique(array_merge($adminIds, $contactIds));

    // 3️⃣ Fetch contacts with role info
    $contacts = User::with('role')
        ->whereIn('id', $contactIds)
        ->get()
        ->map(function($user) {
            $user->role_name = $user->role ? $user->role->name : 'User';
            return $user;
        })
        // Optional: sort so admins appear first
        ->sortByDesc(fn($u) => $u->role_id == 1)
        ->values(); // reindex collection

    // 4️⃣ Fetch messages if a contact is selected
    $messages = [];
    if ($selectedUserId) {
        $messages = Message::where(function ($query) use ($authId, $selectedUserId) {
                    $query->where('sender_id', $authId)->where('receiver_id', $selectedUserId);
                })
                ->orWhere(function ($query) use ($authId, $selectedUserId) {
                    $query->where('sender_id', $selectedUserId)->where('receiver_id', $authId);
                })
                ->orderBy('created_at', 'asc')
                ->get();
    }

    return view('agency.messages', compact('contacts', 'messages', 'selectedUserId'));
}

    /**
     * Store a new message.
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

        return redirect()->route('agency.messages-index', ['user_id' => $request->receiver_id])
                         ->with('success', 'Message sent successfully!');
    }

    /**
     * Update a message.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to edit this message.');
        }

        $message->update(['message' => $request->message]);

        return back()->with('success', 'Message updated successfully!');
    }

    /**
     * Delete a message.
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to delete this message.');
        }

        $message->delete();

        return back()->with('success', 'Message deleted successfully!');
    }
}
