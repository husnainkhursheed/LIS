<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterReportExport implements FromCollection, WithTitle, WithHeadings, WithEvents
{
    protected $rows;
    protected $startDate;
    protected $endDate;

    public function __construct($rows, $startDate, $endDate)
    {
        $this->rows =  $rows;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return [
            'Date Received',
            'Insitution',
            'Doctor',
            'Access #',
            'Patient Name',
            'Test',
            'Total'
        ];
    }

    public function title(): string
    {
        return 'Master Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();


                // ? Bold the header row and apply background color

                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'D1EDF1', // Light cyan background
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);


                // ? Apply border to entire data range

                $rowCount = count($this->rows) + 1;
                $sheet->getStyle("A1:G{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // 👉 Format 'Total' column as currency
                for ($i = 2; $i <= $rowCount; $i++) {
                    $sheet->getStyle("G{$i}")
                        ->getNumberFormat()
                        ->setFormatCode('"$"#,##0.00');
                }

                // 👉 Set column widths for better readability
                $columns = ['A' => 15, 'B' => 20, 'C' => 18, 'D' => 15, 'E' => 20, 'F' => 50, 'G' => 12];
                foreach ($columns as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }
            }
        ];
    }
}
