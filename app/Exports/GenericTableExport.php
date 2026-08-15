<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Renders the same reports.export table markup used for the PDF export into
 * a spreadsheet -- one template drives both formats for every report type.
 */
class GenericTableExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(private array $data)
    {
    }

    public function view(): View
    {
        return view('reports.export', $this->data);
    }

    public function title(): string
    {
        return \Illuminate\Support\Str::limit($this->data['title'] ?? 'Report', 31, '');
    }
}
