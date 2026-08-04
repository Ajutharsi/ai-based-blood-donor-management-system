<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();

        $query = $admin->notifications();
        if (request('status') === 'unread') {
            $query->whereNull('read_at');
        }
        $notifications = $query->paginate(15)->withQueryString();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $admin = auth('admin')->user();

        if ($notification->user_type !== 'admin' || $notification->user_id !== $admin->id) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect($notification->urlFor('admin') ?? route('admin.notifications.index'));
    }

    public function markAllRead()
    {
        $admin = auth('admin')->user();
        $admin->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back();
    }
}
