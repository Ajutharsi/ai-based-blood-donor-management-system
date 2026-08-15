<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GenericTableExport;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Services\ReportService;
use App\Support\ReportFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    // Which generic filters are meaningful for each report type, and what
    // the "status" filter's options/label mean in that context (it maps to
    // a different underlying concept per type -- eligibility, verification,
    // request status, stock level, or AI prediction type).
    private const FILTER_MAP = [
        'donors' => [
            'fields' => ['date', 'blood_group', 'district', 'status'],
            'status_label' => 'Eligibility',
            'status_options' => ['eligible' => 'Eligible', 'not_eligible' => 'Not Eligible'],
        ],
        'hospitals' => [
            'fields' => ['date', 'district', 'status'],
            'status_label' => 'Verification',
            'status_options' => ['verified' => 'Verified', 'pending' => 'Pending'],
        ],
        'blood_requests' => [
            'fields' => ['date', 'blood_group', 'hospital', 'district', 'status'],
            'status_label' => 'Status',
            'status_options' => ['pending' => 'Pending', 'fulfilled' => 'Fulfilled', 'cancelled' => 'Cancelled'],
        ],
        'blood_inventory' => [
            'fields' => ['blood_group', 'hospital', 'district', 'status'],
            'status_label' => 'Stock Level',
            'status_options' => ['sufficient' => 'Sufficient', 'low_stock' => 'Low Stock', 'critical' => 'Critical'],
        ],
        'donations' => [
            'fields' => ['date', 'blood_group', 'hospital', 'district'],
            'status_label' => null,
            'status_options' => [],
        ],
        'ai_predictions' => [
            'fields' => ['date', 'blood_group', 'district', 'status'],
            'status_label' => 'Prediction Type',
            'status_options' => ['eligibility' => 'Eligibility', 'response' => 'Response', 'anomaly' => 'Anomaly'],
        ],
        'activity_logs' => [
            'fields' => ['date', 'status'],
            'status_label' => 'Category',
            'status_options' => [
                'auth' => 'Login / Logout',
                'registration' => 'Registration',
                'profile' => 'Profile Updates',
                'blood_request' => 'Blood Requests',
                'notification' => 'Notifications',
                'inventory' => 'Blood Inventory',
                'donation' => 'Donations',
                'appointment' => 'Appointments',
                'ai_prediction' => 'AI Predictions',
                'admin' => 'Admin Actions',
            ],
        ],
    ];

    public function __construct(private ReportService $reportService)
    {
    }

    private function filters(Request $request): array
    {
        return $request->only(['date_from', 'date_to', 'blood_group', 'hospital_id', 'district', 'status']);
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'donors');
        if (!array_key_exists($type, ReportService::TYPES)) {
            $type = 'donors';
        }

        $filters = $this->filters($request);
        $items = $this->reportService->forType($type, $filters);

        $columns = ReportFormatter::columns($type);
        $allRows = ReportFormatter::rows($type, $items);
        $rows = array_slice($allRows, 0, 50);

        $hospitals = Hospital::orderBy('name')->get(['id', 'name']);
        $districts = collect([Hospital::pluck('district'), \App\Models\Donor::pluck('district')])
            ->flatten()->filter()->unique()->sort()->values();

        return view('admin.reports.index', [
            'type'         => $type,
            'types'        => ReportService::TYPES,
            'columns'      => $columns,
            'rows'         => $rows,
            'totalCount'   => count($allRows),
            'hospitals'    => $hospitals,
            'districts'    => $districts,
            'bloodGroups'  => self::BLOOD_GROUPS,
            'filters'      => $filters,
            'filterConfig' => self::FILTER_MAP[$type],
        ]);
    }

    public function export(Request $request, string $type, string $format)
    {
        if (!array_key_exists($type, ReportService::TYPES)) {
            abort(404);
        }
        if (!in_array($format, ['pdf', 'excel'], true)) {
            abort(404);
        }

        $filters = $this->filters($request);
        $items = $this->reportService->forType($type, $filters);

        $data = [
            'title'          => ReportService::TYPES[$type],
            'generatedAt'    => now()->format('d M Y, h:i A'),
            'filtersSummary' => $this->summarizeFilters($filters),
            'columns'        => ReportFormatter::columns($type),
            'rows'           => ReportFormatter::rows($type, $items),
        ];

        $filename = $type . '-report-' . now()->format('Ymd-His');

        if ($format === 'pdf') {
            return Pdf::loadView('reports.export', $data)->download("{$filename}.pdf");
        }

        return Excel::download(new GenericTableExport($data), "{$filename}.xlsx");
    }

    private function summarizeFilters(array $filters): string
    {
        $parts = [];
        if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
            $parts[] = 'Date: ' . ($filters['date_from'] ?? 'any') . ' to ' . ($filters['date_to'] ?? 'any');
        }
        if (!empty($filters['blood_group'])) {
            $parts[] = 'Blood Group: ' . $filters['blood_group'];
        }
        if (!empty($filters['hospital_id'])) {
            $parts[] = 'Hospital: ' . (Hospital::find($filters['hospital_id'])->name ?? $filters['hospital_id']);
        }
        if (!empty($filters['district'])) {
            $parts[] = 'District: ' . $filters['district'];
        }
        if (!empty($filters['status'])) {
            $parts[] = 'Status: ' . $filters['status'];
        }

        return implode(' | ', $parts);
    }
}
