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
        return [];
    }
}
