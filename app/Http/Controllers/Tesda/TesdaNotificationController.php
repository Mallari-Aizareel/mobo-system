<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class TesdaNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tesda.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);

        return redirect($notif->url ?? route('tesda.notifications'));
    }
}
