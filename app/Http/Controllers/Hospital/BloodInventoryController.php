<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodInventoryLog;
use App\Services\BloodInventoryService;
use Illuminate\Http\Request;

class BloodInventoryController extends Controller
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    public function __construct(private BloodInventoryService $inventoryService)
    {
    }

    // Every blood group always has a row for a logged-in hospital -- rows
    // are lazily created here rather than needing a separate "add a blood
    // group" step, which is what keeps "one record per hospital per blood
    // group" trivially true.
    public function index()
    {
        $hospital = auth('hospital')->user();

        foreach (self::BLOOD_GROUPS as $group) {
            $this->inventoryService->findOrCreate($hospital->id, $group);
        }

        // Sorted in-memory (not via SQL) so this works identically on the
        // SQLite connection used in dev/tests and on MySQL in production.
        $order = array_flip(self::BLOOD_GROUPS);
        $inventory = BloodInventory::where('hospital_id', $hospital->id)->get()
            ->sortBy(fn ($i) => $order[$i->blood_group] ?? 99)
            ->values();

        return view('hospital.inventory', compact('hospital', 'inventory'));
    }

    public function history()
    {
        $hospital = auth('hospital')->user();

        $logs = BloodInventoryLog::where('hospital_id', $hospital->id)
            ->latest('created_at')
            ->paginate(20);

        return view('hospital.inventory_history', compact('hospital', 'logs'));
    }

    public function addUnits(Request $request, BloodInventory $inventory)
    {
        $this->authorizeOwnership($inventory);

        $request->validate([
            'units' => 'required|integer|min:1|max:500',
        ]);

        $this->inventoryService->addUnits($inventory, (int) $request->units, 'Manually added by hospital');

        return back()->with('success', "Added {$request->units} unit(s) of {$inventory->blood_group}.");
    }

    public function removeUnits(Request $request, BloodInventory $inventory)
    {
        $this->authorizeOwnership($inventory);

        $request->validate([
            'units' => 'required|integer|min:1|max:500',
        ]);

        $result = $this->inventoryService->removeUnits($inventory, (int) $request->units, 'Manually removed by hospital');

        if (!$result['fully_honoured']) {
            return back()->with('warning', "Only {$result['removed']} unit(s) of {$inventory->blood_group} were available -- removed all of them, stock is now 0.");
        }

        return back()->with('success', "Removed {$request->units} unit(s) of {$inventory->blood_group}.");
    }

    public function updateThreshold(Request $request, BloodInventory $inventory)
    {
        $this->authorizeOwnership($inventory);

        $request->validate([
            'minimum_threshold' => 'required|integer|min:0|max:1000',
        ]);

        $this->inventoryService->updateThreshold($inventory, (int) $request->minimum_threshold);

        return back()->with('success', "Minimum threshold for {$inventory->blood_group} updated to {$request->minimum_threshold}.");
    }

    private function authorizeOwnership(BloodInventory $inventory): void
    {
        if ($inventory->hospital_id !== auth('hospital')->id()) {
            abort(403);
        }
    }
}
