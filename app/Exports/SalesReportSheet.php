<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $data;
    protected $tableName;
    protected $month;
    protected $year;

    public function __construct($data, $tableName, $month, $year)
    {
        $this->data = $data;
        $this->tableName = $tableName;
        $this->month = $month;
        $this->year = $year;

        // Debugging
        Log::info("SalesReportSheet constructed for table: $tableName");
        Log::info("Data sample:", array_slice($data->toArray(), 0, 2));
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            switch ($this->tableName) {
                case 'orderMotors':
                    return [
                        $item->user->name ?? 'N/A',
                        $item->motor->nama_motor ?? 'N/A',
                        $item->warna->nama_warna ?? 'N/A',
                        $item->nomor_rangka ?? 'N/A',
                        $item->nomor_mesin ?? 'N/A',
                        $item->jumlah ?? 'N/A',
                        $item->harga_jual ?? 'N/A',
                        $this->formatDate($item->created_at)
                    ];
                case 'orderSpareParts':
                    return [
                        $item->user->name ?? 'N/A',
                        $item->sparePart->nama_spare_part ?? 'N/A',
                        $item->jumlah ?? 'N/A',
                        $item->harga_jual ?? 'N/A',
                        $this->formatDate($item->created_at)
                    ];
                case 'soldMotors':
                    return [
                        $item->motor->nama_motor ?? 'N/A',
                        $item->warna->nama_warna ?? 'N/A',
                        $item->nomor_rangka ?? 'N/A',
                        $item->nomor_mesin ?? 'N/A',
                        $item->harga_jual ?? 'N/A',
                        $this->formatDate($item->tanggal_terjual)
                    ];
                case 'soldSpareParts':
                    return [
                        $item->sparePart->nama_spare_part ?? 'N/A',
                        $item->jumlah ?? 'N/A',
                        $item->harga_jual ?? 'N/A',
                        $this->formatDate($item->tanggal_terjual)
                    ];
                default:
                    return [];
            }
        });
    }

    private function formatDate($date)
    {
        if ($date instanceof \Carbon\Carbon) {
            return $date->format('Y-m-d H:i:s');
        } elseif (is_string($date)) {
            return $date;
        } else {
            return 'N/A';
        }
    }

    public function title(): string
    {
        return ucfirst($this->tableName);
    }

    public function headings(): array
    {
        // Define headings based on the table
        switch ($this->tableName) {
            case 'orderMotors':
                return ['User', 'Motor', 'Warna', 'Nomor Rangka', 'Nomor Mesin', 'Quantity', 'Harga Jual', 'Tanggal Order'];
            case 'orderSpareParts':
                return ['User', 'Spare Part', 'Quantity', 'Harga Jual', 'Tanggal Order'];
            case 'soldMotors':
                return ['Motor', 'Warna', 'Nomor Rangka', 'Nomor Mesin', 'Harga Jual', 'Tanggal Terjual'];
            case 'soldSpareParts':
                return ['Spare Part', 'Quantity', 'Harga Jual', 'Tanggal Terjual'];
            default:
                return [];
        }
    }
}
