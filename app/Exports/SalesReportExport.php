<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    protected $reportData;
    protected $tables;
    protected $month;
    protected $year;
    protected $tableNames;

    public function __construct($reportData, $tables, $month, $year, $tableNames)
    {
        $this->reportData = $reportData;
        $this->tables = $tables;
        $this->month = $month;
        $this->year = $year;
        $this->tableNames = $tableNames;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->tables as $table) {
            if (isset($this->reportData[$table]) && count($this->reportData[$table]) > 0) {
                Log::info("Creating sheet for table: $table", [
                    'sample_item' => $this->reportData[$table]->first(),
                    'date_field_type' => $table === 'orderMotors' || $table === 'orderSpareParts' 
                        ? gettype($this->reportData[$table]->first()->created_at)
                        : gettype($this->reportData[$table]->first()->tanggal_terjual),
                ]);
                
                $sheets[] = new SalesReportSheet(
                    $this->reportData[$table],
                    $table,
                    $this->month,
                    $this->year
                );
            }
        }
        return $sheets;
    }
}
