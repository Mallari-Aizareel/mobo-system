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
        $selectedUserId = $request->user_id;

        // Fetch all non-admin users as contacts
        $contacts = User::where('id', '!=', Auth::id())
            ->orderBy('firstname')
            ->get();

        $messages = collect();
        if ($selectedUserId) {
            $messages = Message::where(function ($query) use ($selectedUserId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $selectedUserId);
            })->orWhere(function ($query) use ($selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
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
