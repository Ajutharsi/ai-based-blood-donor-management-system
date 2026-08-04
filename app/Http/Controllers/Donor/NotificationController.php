<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $donor = auth('donor')->user();

        $query = $donor->notifications();
        if (request('status') === 'unread') {
            $query->whereNull('read_at');
        }
        $notifications = $query->paginate(15)->withQueryString();

        return view('donor.notifications', compact('donor', 'notifications'));
    }

    // Marks a notification read, then sends the donor on to whatever it's
    // about (if anything) -- one click both dismisses the alert and takes
    // them to the relevant page, rather than requiring a second click.
    public function markRead(Notification $notification)
    {
        $donor = auth('donor')->user();

        if ($notification->user_type !== 'donor' || $notification->user_id !== $donor->id) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect($notification->urlFor('donor') ?? route('donor.notifications.index'));
    }

    public function markAllRead()
    {
        $donor = auth('donor')->user();
        $donor->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back();
    }
}
