<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Turns a ReportService collection into the plain column-headers + row-of-
 * strings shape that the shared export view (and both the PDF and Excel
 * renderers of it) expect, so every report type funnels through one export
 * template instead of six.
 */
class ReportFormatter
{
    public static function columns(string $type): array
    {
        return match ($type) {
            'donors' => ['Name', 'Email', 'Blood Group', 'District', 'Eligible', 'Total Donations', 'Registered'],
            'hospitals' => ['Name', 'Email', 'District', 'Verified', 'Total Requests', 'Registered'],
            'blood_requests' => ['Hospital', 'Blood Group', 'Units', 'Urgency', 'Status', 'Ward', 'Created'],
            'blood_inventory' => ['Hospital', 'Blood Group', 'Available Units', 'Minimum Threshold', 'Status', 'Last Updated'],
            'donations' => ['Donor', 'Blood Group', 'Donation Center', 'Units', 'Donation Date'],
            'ai_predictions' => ['Donor', 'Prediction Type', 'Model', 'Confidence', 'Created'],
            'activity_logs' => ['Category', 'Action', 'Actor', 'Description', 'IP Address', 'When'],
            default => [],
        };
    }

    public static function rows(string $type, Collection $items): array
    {
        return match ($type) {
            'donors' => $items->map(fn ($d) => [
                $d->full_name,
                $d->email,
                $d->blood_group ?? '—',
                $d->district ?? '—',
                $d->is_eligible ? 'Yes' : 'No',
                (string) $d->total_donations,
                $d->created_at->format('d M Y'),
            ])->all(),

            'hospitals' => $items->map(fn ($h) => [
                $h->name,
                $h->email,
                $h->district ?? '—',
                $h->is_verified ? 'Yes' : 'No',
                (string) $h->bloodRequests()->count(),
                $h->created_at->format('d M Y'),
            ])->all(),

            'blood_requests' => $items->map(fn ($r) => [
                $r->hospital?->name ?? 'Deleted hospital',
                $r->blood_group,
                (string) $r->units_needed,
                ucfirst($r->urgency),
                ucfirst($r->status),
                $r->ward ?? '—',
                $r->created_at->format('d M Y'),
            ])->all(),

            'blood_inventory' => $items->map(fn ($i) => [
                $i->hospital?->name ?? 'Deleted hospital',
                $i->blood_group,
                (string) $i->available_units,
                (string) $i->minimum_threshold,
                $i->statusLabel(),
                $i->last_updated ? $i->last_updated->format('d M Y') : '—',
            ])->all(),

            'donations' => $items->map(fn ($d) => [
                $d->donor?->full_name ?? 'Deleted donor',
                $d->blood_group ?? '—',
                $d->donation_center ?? '—',
                (string) $d->units,
                $d->donation_date->format('d M Y'),
            ])->all(),

            'ai_predictions' => $items->map(fn ($p) => [
                $p->donor?->full_name ?? 'Deleted donor',
                ucfirst($p->prediction_type),
                $p->model,
                $p->confidence !== null ? number_format($p->confidence, 2) : '—',
                $p->created_at->format('d M Y'),
            ])->all(),

            'activity_logs' => $items->map(fn ($l) => [
                ucfirst(str_replace('_', ' ', $l->category)),
                str_replace('_', ' ', $l->action),
                ucfirst($l->actor_type) . ($l->actor_name ? " ({$l->actor_name})" : ''),
                $l->description,
                $l->ip_address ?? '—',
                $l->created_at->format('d M Y h:i A'),
            ])->all(),

            default => [],
        };
    }
}
