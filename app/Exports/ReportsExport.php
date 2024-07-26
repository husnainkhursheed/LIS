<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ReportsExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            ['Date Range:', '01-Jul-2024', '', '', '', '15-Jul-2024'],
            [],
            ['Department:','Test #', 'Access #', 'Test', 'Date Received', 'Date Completed', 'In Progress', 'Number of Days'],
            [ 'BioChemistry / Haematology'],
        ];
    }
}
