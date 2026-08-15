<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Every category the module tracks -- keys match the `category` column
    // written by ActivityLog::log()/record() calls across the app.
    public const CATEGORIES = [
        'auth'          => 'Login / Logout',
        'registration'  => 'Registration',
        'profile'       => 'Profile Updates',
        'blood_request' => 'Blood Requests',
        'notification'  => 'Notifications',
        'inventory'     => 'Blood Inventory',
        'donation'      => 'Donations',
        'appointment'   => 'Appointments',
        'ai_prediction' => 'AI Predictions',
        'admin'         => 'Admin Actions',
    ];

    public const ACTOR_TYPES = [
        'donor'    => 'Donor',
        'hospital' => 'Hospital',
        'admin'    => 'Admin',
        'system'   => 'System',
    ];

    // Search + filter + paginate every activity log entry -- the admin-only
    // audit trail across every tracked category.
    public function index(Request $request)
    {
        $query = ActivityLog::query()->latest('created_at');

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], '', $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                  ->orWhere('actor_name', 'like', "%$search%")
                  ->orWhere('action', 'like', "%$search%");
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('actor_type')) {
            $query->where('actor_type', $request->actor_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.activity_logs.index', [
            'logs'       => $logs,
            'categories' => self::CATEGORIES,
            'actorTypes' => self::ACTOR_TYPES,
            'filters'    => $request->only(['search', 'category', 'actor_type', 'date_from', 'date_to']),
        ]);
    }
}
