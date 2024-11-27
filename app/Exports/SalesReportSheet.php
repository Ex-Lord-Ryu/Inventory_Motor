<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $data;
    protected $table;
    protected $month;
    protected $year;

    public function __construct($data, $table, $month, $year)
    {
        $this->data = $data;
        $this->table = $table;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return ucfirst($this->table);
    }

    public function headings(): array
    {
        // Define headings based on the table
        switch ($this->table) {
            case 'orderMotors':
                return ['User', 'Motor', 'Color', 'Frame Number', 'Engine Number', 'Quantity', 'Selling Price', 'Order Date'];
            case 'orderSpareParts':
                return ['User', 'Spare Part', 'Quantity', 'Selling Price', 'Order Date'];
            case 'soldMotors':
                return ['Motor', 'Color', 'Frame Number', 'Engine Number', 'Selling Price', 'Sold Date'];
            case 'soldSpareParts':
                return ['Spare Part', 'Quantity', 'Selling Price', 'Sold Date'];
            default:
                return [];
        }
    }
}