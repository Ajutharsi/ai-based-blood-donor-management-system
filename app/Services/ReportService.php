<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\AiPrediction;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Builds the filtered dataset behind every report type. Every method takes
 * the same generic filter shape (date_from, date_to, blood_group,
 * hospital_id, district, status) and applies whichever of those are
 * meaningful for that report -- callers don't need to know which filters a
 * given report type actually honours. $scopeHospital, when passed, forces
 * the result to that hospital's own data regardless of any hospital_id
 * filter supplied -- this is what keeps a hospital's report exports scoped
 * to itself.
 */
class ReportService
{
    public const TYPES = [
        'donors'          => 'Donor Report',
        'hospitals'       => 'Hospital Report',
        'blood_requests'  => 'Blood Request Report',
        'blood_inventory' => 'Blood Inventory Report',
        'donations'       => 'Donation Report',
        'ai_predictions'  => 'AI Prediction Report',
        'activity_logs'   => 'Activity Log Report',
    ];

    // Types a hospital account is allowed to run -- donor PII, other
    // hospitals' records, and the AI audit log stay admin-only, matching
    // every other cross-hospital access boundary already enforced
    // elsewhere in the app (BloodInventoryController, DonorController, etc).
    public const HOSPITAL_TYPES = ['blood_requests', 'blood_inventory', 'donations'];

    private function dateRange(Builder $query, array $filters, string $column): Builder
    {
        if (!empty($filters['date_from'])) {
            $query->whereDate($column, '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate($column, '<=', $filters['date_to']);
        }

        return $query;
    }

    public function donors(array $filters): Collection
    {
        $query = Donor::query();
        $this->dateRange($query, $filters, 'created_at');

        if (!empty($filters['blood_group'])) {
            $query->where('blood_group', $filters['blood_group']);
        }
        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }
        if (!empty($filters['status'])) {
            $query->where('is_eligible', $filters['status'] === 'eligible');
        }

        return $query->latest()->get();
    }

    public function hospitals(array $filters): Collection
    {
        $query = Hospital::query();
        $this->dateRange($query, $filters, 'created_at');

        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }
        if (!empty($filters['status'])) {
            $query->where('is_verified', $filters['status'] === 'verified');
        }

        return $query->latest()->get();
    }

    public function bloodRequests(array $filters, ?Hospital $scopeHospital = null): Collection
    {
        $query = BloodRequest::with('hospital');
        $this->dateRange($query, $filters, 'created_at');

        if ($scopeHospital) {
            $query->where('hospital_id', $scopeHospital->id);
        } elseif (!empty($filters['hospital_id'])) {
            $query->where('hospital_id', $filters['hospital_id']);
        }
        if (!empty($filters['blood_group'])) {
            $query->where('blood_group', $filters['blood_group']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['district'])) {
            $query->whereHas('hospital', fn ($q) => $q->where('district', $filters['district']));
        }

        return $query->latest()->get();
    }

    public function bloodInventory(array $filters, ?Hospital $scopeHospital = null): Collection
    {
        $query = BloodInventory::with('hospital');

        if ($scopeHospital) {
            $query->where('hospital_id', $scopeHospital->id);
        } elseif (!empty($filters['hospital_id'])) {
            $query->where('hospital_id', $filters['hospital_id']);
        }
        if (!empty($filters['blood_group'])) {
            $query->where('blood_group', $filters['blood_group']);
        }
        if (!empty($filters['district'])) {
            $query->whereHas('hospital', fn ($q) => $q->where('district', $filters['district']));
        }

        $rows = $query->get();

        // status (Sufficient/Low Stock/Critical) is computed, not a column,
        // so it's filtered in memory after the rest of the query narrows it.
        if (!empty($filters['status'])) {
            $rows = $rows->filter(fn ($item) => $item->status() === $filters['status'])->values();
        }

        return $rows;
    }

    public function donations(array $filters, ?Hospital $scopeHospital = null): Collection
    {
        $query = Donation::query();
        $this->dateRange($query, $filters, 'donation_date');

        // Donations aren't linked to a hospital by foreign key -- the
        // completion flow (AppointmentService::complete) stamps
        // donation_center with the hospital's name, which is the only
        // available join back to a hospital for scoping/filtering.
        if ($scopeHospital) {
            $query->where('donation_center', $scopeHospital->name);
        } elseif (!empty($filters['hospital_id'])) {
            $hospitalName = Hospital::where('id', $filters['hospital_id'])->value('name');
            $query->where('donation_center', $hospitalName);
        }
        if (!empty($filters['blood_group'])) {
            $query->where('blood_group', $filters['blood_group']);
        }
        if (!empty($filters['district'])) {
            $districtHospitalNames = Hospital::where('district', $filters['district'])->pluck('name');
            $query->whereIn('donation_center', $districtHospitalNames);
        }

        return $query->with('donor')->latest('donation_date')->get();
    }

    public function aiPredictions(array $filters): Collection
    {
        $query = AiPrediction::with('donor');
        $this->dateRange($query, $filters, 'created_at');

        if (!empty($filters['status'])) {
            $query->where('prediction_type', $filters['status']);
        }
        if (!empty($filters['blood_group'])) {
            $query->whereHas('donor', fn ($q) => $q->where('blood_group', $filters['blood_group']));
        }
        if (!empty($filters['district'])) {
            $query->whereHas('donor', fn ($q) => $q->where('district', $filters['district']));
        }

        return $query->latest()->get();
    }

    public function activityLogs(array $filters): Collection
    {
        $query = ActivityLog::query();
        $this->dateRange($query, $filters, 'created_at');

        if (!empty($filters['status'])) {
            $query->where('category', $filters['status']);
        }

        return $query->latest('created_at')->get();
    }

    public function forType(string $type, array $filters, ?Hospital $scopeHospital = null): Collection
    {
        return match ($type) {
            'donors'          => $this->donors($filters),
            'hospitals'       => $this->hospitals($filters),
            'blood_requests'  => $this->bloodRequests($filters, $scopeHospital),
            'blood_inventory' => $this->bloodInventory($filters, $scopeHospital),
            'donations'       => $this->donations($filters, $scopeHospital),
            'ai_predictions'  => $this->aiPredictions($filters),
            'activity_logs'   => $this->activityLogs($filters),
            default           => collect(),
        };
    }
}
