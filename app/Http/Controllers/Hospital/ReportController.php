<?php

namespace App\Http\Controllers\Hospital;

use App\Exports\GenericTableExport;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Support\ReportFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    // Hospitals only ever run reports scoped to themselves -- ReportService
    // enforces that scoping, this map just controls which of the three
    // hospital-allowed types are offered and which generic filters make
    // sense for each (no hospital filter here: it's implicitly "self").
    private const FILTER_MAP = [
        'blood_requests' => [
            'fields' => ['date', 'blood_group', 'status'],
            'status_label' => 'Status',
            'status_options' => ['pending' => 'Pending', 'fulfilled' => 'Fulfilled', 'cancelled' => 'Cancelled'],
        ],
        'blood_inventory' => [
            'fields' => ['blood_group', 'status'],
            'status_label' => 'Stock Level',
            'status_options' => ['sufficient' => 'Sufficient', 'low_stock' => 'Low Stock', 'critical' => 'Critical'],
        ],
        'donations' => [
            'fields' => ['date', 'blood_group'],
            'status_label' => null,
            'status_options' => [],
        ],
    ];

    public function __construct(private ReportService $reportService)
    {
    }

    private function filters(Request $request): array
    {
        return $request->only(['date_from', 'date_to', 'blood_group', 'district', 'status']);
    }

    public function index(Request $request)
    {
        $hospitalTypes = array_intersect_key(ReportService::TYPES, array_flip(ReportService::HOSPITAL_TYPES));

        $type = $request->get('type', 'blood_requests');
        if (!array_key_exists($type, $hospitalTypes)) {
            $type = 'blood_requests';
        }

        $hospital = auth('hospital')->user();
        $filters = $this->filters($request);
        $items = $this->reportService->forType($type, $filters, $hospital);

        $columns = ReportFormatter::columns($type);
        $allRows = ReportFormatter::rows($type, $items);
        $rows = array_slice($allRows, 0, 50);

        return view('hospital.reports', [
            'hospital'     => $hospital,
            'type'         => $type,
            'types'        => $hospitalTypes,
            'columns'      => $columns,
            'rows'         => $rows,
            'totalCount'   => count($allRows),
            'bloodGroups'  => self::BLOOD_GROUPS,
            'filters'      => $filters,
            'filterConfig' => self::FILTER_MAP[$type],
        ]);
    }

    public function export(Request $request, string $type, string $format)
    {
        if (!in_array($type, ReportService::HOSPITAL_TYPES, true)) {
            abort(403);
        }
        if (!in_array($format, ['pdf', 'excel'], true)) {
            abort(404);
        }

        $hospital = auth('hospital')->user();
        $filters = $this->filters($request);
        $items = $this->reportService->forType($type, $filters, $hospital);

        $data = [
            'title'          => $hospital->name . ' — ' . ReportService::TYPES[$type],
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
        if (!empty($filters['status'])) {
            $parts[] = 'Status: ' . $filters['status'];
        }

        return implode(' | ', $parts);
    }
}
