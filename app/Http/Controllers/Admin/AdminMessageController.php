<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        $authId = Auth::id();
        $selectedUserId = $request->user_id;

        // Get all user IDs that have messages with admin
        $contactIds = Message::where('sender_id', $authId)
                        ->orWhere('receiver_id', $authId)
                        ->get(['sender_id', 'receiver_id'])
                        ->flatMap(function ($msg) use ($authId) {
                            return $msg->sender_id == $authId ? [$msg->receiver_id] : [$msg->sender_id];
                        })
                        ->unique()
                        ->toArray();

        // Exclude admin itself
        $contactIds = array_filter($contactIds, fn($id) => $id != $authId);

        // Fetch contacts with latest message timestamp
        $contacts = User::whereIn('id', $contactIds)
            ->get()
            ->map(function ($user) use ($authId) {
                $user->role_name = $user->role ? $user->role->name : 'User';

                // Get the latest message timestamp with this user
                $latestMessage = Message::where(function ($query) use ($authId, $user) {
                        $query->where('sender_id', $authId)->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($query) use ($authId, $user) {
                        $query->where('sender_id', $user->id)->where('receiver_id', $authId);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

                $user->latest_message_at = $latestMessage?->created_at ?? null;

                return $user;
            })
            ->sortByDesc('latest_message_at') // Sort by latest message
            ->values();

        // Fetch messages with selected user
        $messages = collect();
        if ($selectedUserId) {
            $messages = Message::where(function ($query) use ($authId, $selectedUserId) {
                    $query->where('sender_id', $authId)
                          ->where('receiver_id', $selectedUserId);
                })
                ->orWhere(function ($query) use ($authId, $selectedUserId) {
                    $query->where('sender_id', $selectedUserId)
                          ->where('receiver_id', $authId);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('admin.messages', compact('contacts', 'selectedUserId', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
