<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TimeTakenReportExport implements WithMultipleSheets
{
    protected $grouped;
    protected $startDate;
    protected $endDate;
    protected $departmentSummary;

    public function __construct($grouped, $startDate, $endDate, $departmentSummary)
    {
        $this->grouped = $grouped;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->departmentSummary = $departmentSummary;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->grouped as $department => $rows) {
            $sheets[] = new \App\Exports\TimeTakenDepartmentSheetExport(
                $department,
                $rows,
                $this->startDate,
                $this->endDate,
                $this->departmentSummary[$department] ?? []
            );
        }
        return $sheets;
    }
}
