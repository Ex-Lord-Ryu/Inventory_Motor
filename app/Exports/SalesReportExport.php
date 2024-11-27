<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    protected $reportData;
    protected $tables;
    protected $month;
    protected $year;

    public function __construct($reportData, $tables, $month, $year)
    {
        $this->reportData = $reportData;
        $this->tables = $tables;
        $this->month = $month;
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->tables as $table) {
            if (isset($this->reportData[$table]) && !empty($this->reportData[$table])) {
                $sheets[] = new SalesReportSheet($this->reportData[$table], $table, $this->month, $this->year);
            }
        }

        return $sheets;
    }
}