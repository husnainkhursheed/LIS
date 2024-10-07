<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TimeTakenReportExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $data;
    protected $startDate;
    protected $endDate;
    protected $summary;

    public function __construct($data, $startDate, $endDate, $summary)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->summary = $summary;
    }

    // Collection method will return the data for the Excel sheet
    public function collection()
    {
        $rows = [];

        foreach ($this->data as $department => $samples) {
            // Add department heading
            $departmentName = $this->getDepartmentName($department);
            $rows[] = [$departmentName];
            $rows[] = [''];
            // Add headers for each department
            $rows[] = [ '','Test #', 'Access #', 'Date Received', 'Date Completed', 'In Progress', 'Number of Days'];

            foreach ($samples as $sample) {
                $rows[] = [
                    '',
                    $sample['Test ID'],
                    $sample['Access # '],
                    $sample['Date Received'],
                    $sample['Date Completed'],
                    $sample['In Progress'],
                    $sample['Number of Days']
                ];
            }

            $rows[] = [''];


            // Add totals for the department
            $rows[] = [
                '',
                'Total # of Days',
                '',
                '',
                '',
                '',
                $this->summary[$department]['total_days']
            ];
            $rows[] = [
                '',
                'Avg # of Days',
                '',
                '',
                '',
                '',
                $this->summary[$department]['avg_days']
            ];
            $rows[] = [
                '',
                'Total Outliers',
                '',
                '',
                '',
                '',
                $this->summary[$department]['total_outliers']
            ];
            $rows[] = [
                '',
                'Outliers (%)',
                '',
                '',
                '',
                '',
                $this->summary[$department]['outliers_percentage'] . '%'
            ];
            $rows[] = [
                '',
                'Total Tests: Completed',
                '',
                '',
                '',
                '',
                $this->summary[$department]['total_completed']
            ];
            $rows[] = [
                '',
                'Total Tests: Pending',
                '',
                '',
                '',
                '',
                $this->summary[$department]['total_pending']
            ];

            // Add a blank row for spacing between departments
            $rows[] = [''];
        }

        return collect($rows);
    }

    private function getDepartmentName($departmentId)
    {
        switch ($departmentId) {
            case 1:
                return 'BioChemistry / Haematology';
            case 2:
                return 'Cytology / Gynecology';
            case 3:
                return 'Urinalysis / Microbiology';
            default:
                return 'Unknown Department';
        }
    }


    // Define headings (if needed for the sheet header)
    public function headings(): array
    {
        return ['Department Reports'];
    }

    // Styles method to apply custom styles like borders, font, and alignment
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Example: Apply bold styling to department headings and table headers
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();

            // If the row is a department heading (based on content), make it bold
            if (in_array($cellValue, ['BioChemistry / Haematology', 'Cytology / Gynecology', 'Urinalysis / Microbiology'])) {
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            }

            // Make column headings bold
            if (in_array($cellValue, ['Test #'])) {
                $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
            }
        }
    }

    // Optional: Event listener to auto-size columns and apply other formatting
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto-size columns
                foreach (range('A', 'F') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }

                // Apply borders to all cells
                $event->sheet->getDelegate()->getStyle('A1:F' . $event->sheet->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);   
            },
        ];
    }
}
