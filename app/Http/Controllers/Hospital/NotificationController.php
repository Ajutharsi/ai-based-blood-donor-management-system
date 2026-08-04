<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $hospital = auth('hospital')->user();

        $query = $hospital->notifications();
        if (request('status') === 'unread') {
            $query->whereNull('read_at');
        }
        $notifications = $query->paginate(15)->withQueryString();

        return view('hospital.notifications', compact('hospital', 'notifications'));
    }

    public function markRead(Notification $notification)
    {
        $hospital = auth('hospital')->user();

        if ($notification->user_type !== 'hospital' || $notification->user_id !== $hospital->id) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect($notification->urlFor('hospital') ?? route('hospital.notifications.index'));
    }

    public function markAllRead()
    {
        $hospital = auth('hospital')->user();
        $hospital->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back();
    }
}
