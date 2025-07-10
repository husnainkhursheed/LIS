<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TimeTakenDepartmentSheetExport implements FromCollection, WithTitle, WithHeadings, WithEvents
{
    protected $department;
    protected $rows;
    protected $startDate;
    protected $endDate;
    protected $summary;

    public function __construct($department, $rows, $startDate, $endDate, $summary)
    {
        $this->department = $department;
        $this->rows = array_map(function($row) {
            // Only remove if the row is an array and has at least 2 elements
            return is_array($row) && count($row) > 1 ? array_slice($row, 1) : $row;
        }, $rows);
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->summary = $summary;
        // dd($this->summary);
    }

    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return [
            // 'Department',
            'Access #',
            'Patient Name',
            'Request',
            'Date Received',
            'Date Completed',
            'In Progress',
            'TAT'
        ];
    }

    public function title(): string
    {
        $names = [
            '1' => 'BioChemistry / Haematology',
            '2' => 'Cytology / Gynecology',
            '3' => 'Urinalysis / Microbiology',
        ];
        return $names[$this->department] ?? 'Department ' . $this->department;
    }
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        $boldLabels = [
            'Department',
            'Access #',
            'Patient Name',
            'Request',
            'Date Received',
            'Date Completed',
            'In Progress',
            'Number of Days',
            'Avg Turnaround Time',
            'Total Outside TAT',
            'Total (%) Outside TAT',
            'Total Inside TAT',
            'Total (%) Inside TAT',
            'Total Tests: Completed',
            'Total Tests: Pending',
            'Grand Total (Pending and Completed)',
        ];

        for ($row = 1; $row <= $lastRow; $row++) {
            // Check all columns in the row for a bold label
            foreach (range('A', 'H') as $col) {
                $cellValue = $sheet->getCell($col . $row)->getValue();
                if (in_array(trim($cellValue), $boldLabels)) {
                    // Make the whole row bold
                    $sheet->getStyle("A{$row}:H{$row}")->getFont()->setBold(true);
                    break;
                }
            }
        }
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $summary = $this->summary ?? [];

                // Find the next empty row after the data
                $rowCount = count($this->rows) + 2; // +1 for headings, +1 for 1-based index

                $summaryRows = [
                    [''],
                    ['', 'Avg Turnaround Time', '', '', '', '', '', $summary['avg_tat'] ?? ''],
                    ['', 'Total Outside TAT', '', '', '', '', '', $summary['total_outside_tat'] ?? ''],
                    ['', 'Total (%) Outside TAT', '', '', '', '', '', $summary['percent_outside_tat'] ?? ''],
                    ['', 'Total Inside TAT', '', '', '', '', '', $summary['total_inside_tat'] ?? ''],
                    ['', 'Total (%) Inside TAT', '', '', '', '', '', $summary['percent_inside_tat'] ?? ''],
                    ['', 'Total Tests: Completed', '', '', '', '', '', $summary['total_completed'] ?? ''],
                    ['', 'Total Tests: Pending', '', '', '', '', '', $summary['total_pending'] ?? ''],
                    ['', 'Grand Total (Pending and Completed)', '', '', '', '', '', $summary['grand_total_days'] ?? ''],
                ];

                foreach ($summaryRows as $row) {
                    $event->sheet->getDelegate()->fromArray($row, null, 'A' . $rowCount++);
                }
            }
        ];
    }
}
