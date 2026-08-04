<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\Hospital;
use Illuminate\Http\Request;

class BloodInventoryController extends Controller
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    // Read-only, cross-hospital view -- admins can see every hospital's
    // stock levels but never edit them (only the owning hospital can).
    public function index(Request $request)
    {
        $query = BloodInventory::with('hospital')->whereHas('hospital');

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $inventory = $query->get()->sortBy([
            fn ($i) => $i->hospital?->name ?? '',
            fn ($i) => $i->blood_group,
        ])->values();

        $totalUnits = BloodInventory::sum('available_units');
        $lowStockCount = $inventory->filter(fn ($i) => $i->status() !== 'sufficient')->count();
        $criticalGroups = $inventory->filter(fn ($i) => $i->status() === 'critical')
            ->pluck('blood_group')->unique()->values();
        $lowStockHospitals = $inventory->filter(fn ($i) => $i->status() !== 'sufficient')
            ->pluck('hospital.name')->filter()->unique()->values();

        $hospitals = Hospital::orderBy('name')->get(['id', 'name']);

        return view('admin.inventory.index', compact(
            'inventory', 'hospitals', 'totalUnits', 'lowStockCount', 'criticalGroups', 'lowStockHospitals'
        ));
    }
}
