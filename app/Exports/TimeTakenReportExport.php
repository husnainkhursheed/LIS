<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TimeTakenReportExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $reportData;
    protected $startDate;
    protected $endDate;
    protected $summary;

    public function __construct(array $reportData, string $startDate, string $endDate, array $summary)
    {
        $this->reportData = $reportData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->summary = $summary;
    }

    public function array(): array
    {
        return array_merge($this->reportData, [
            [],
            [],
            [],
            [],
            [],
            ['Total # of Days', $this->summary['total_days']],
            ['Avg # of Days', $this->summary['avg_days']],
            ['Total Outliers', $this->summary['total_outliers']],
            ['Outliers (%)', $this->summary['outliers_percentage']],
            ['Total Tests: Completed', $this->summary['total_completed']],
            ['Total Tests: Pending', $this->summary['total_pending']],
        ]);
    }

    public function headings(): array
    {
        return [
            ['Date Range:', $this->startDate, $this->endDate],
            [],
            [ 'Test #', 'Access #',  'Date Received', 'Date Completed', 'In Progress', 'Number of Days'],
            [],
            [],
            [],
        ];
    }

    public function title(): string
    {
        return 'Turnaround Time Report';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:D1');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $sheet->getStyle('A3:H3')->getFont()->setBold(true);
        $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal('center');

        return [
            'A1:D1' => ['font' => ['bold' => true]],
            'A3:H3' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}
