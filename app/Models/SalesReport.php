<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    public static function getReportData($month, $year)
    {
        $soldMotors = SoldMotor::whereYear('tanggal_terjual', $year)
            ->whereMonth('tanggal_terjual', $month)
            ->with(['motor', 'warna'])
            ->get();

        $orderMotors = OrderMotor::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['motor', 'warna', 'user'])
            ->get();

        $orderSpareParts = OrderSparePart::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['sparePart', 'user'])
            ->get();

        $soldSpareParts = SoldSparePart::whereYear('tanggal_terjual', $year)
            ->whereMonth('tanggal_terjual', $month)
            ->with('sparePart')
            ->get();

        return [
            'soldMotors' => $soldMotors,
            'orderMotors' => $orderMotors,
            'orderSpareParts' => $orderSpareParts,
            'soldSpareParts' => $soldSpareParts,
        ];
    }
}