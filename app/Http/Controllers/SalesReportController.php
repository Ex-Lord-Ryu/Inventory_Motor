<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderMotor;
use App\Models\OrderSparePart;
use App\Models\SoldMotor;
use App\Models\SoldSparePart;
use Carbon\Carbon;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class SalesReportController extends Controller
{
    protected $tableNames = [
        'orderMotors' => 'Order Motors',
        'orderSpareParts' => 'Order Spare Parts',
        'soldMotors' => 'Sold Motors',
        'soldSpareParts' => 'Sold Spare Parts'
    ];

    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $tables = $request->input('tables', []);
        $export = $request->input('export');

        $reportData = [];

        if (!empty($tables)) {
            $reportData = $this->getReportData($month, $year, $tables);
        }

        if ($export) {
            return $this->export($export, $reportData, $tables, $month, $year);
        }

        $allTables = array_keys($this->tableNames);
        return view('layouts.sales_report.index', [
            'reportData' => $reportData,
            'month' => $month,
            'year' => $year,
            'tables' => $tables,
            'allTables' => $allTables,
            'tableNames' => $this->tableNames
        ]);
    }

    protected function getReportData($month, $year, $tables)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $reportData = [];

        if (in_array('orderMotors', $tables)) {
            $reportData['orderMotors'] = OrderMotor::whereBetween('created_at', [$startDate, $endDate])
                ->with(['user', 'motor', 'warna'])
                ->get();
        }

        if (in_array('orderSpareParts', $tables)) {
            $reportData['orderSpareParts'] = OrderSparePart::whereBetween('created_at', [$startDate, $endDate])
                ->with(['user', 'sparePart'])
                ->get();
        }

        if (in_array('soldMotors', $tables)) {
            $reportData['soldMotors'] = SoldMotor::whereBetween('tanggal_terjual', [$startDate, $endDate])
                ->with(['motor', 'warna'])
                ->get();
        }

        if (in_array('soldSpareParts', $tables)) {
            $reportData['soldSpareParts'] = SoldSparePart::whereBetween('tanggal_terjual', [$startDate, $endDate])
                ->with(['sparePart'])
                ->get();
        }

        return $reportData;
    }

    protected function export($type, $reportData, $tables, $month, $year)
    {
        $fileName = "sales_report_{$year}_{$month}";

        if ($type === 'excel') {
            return Excel::download(new SalesReportExport($reportData, $tables, $month, $year, $this->tableNames), $fileName . '.xlsx');
        } elseif ($type === 'pdf') {
            $pdf = PDF::loadView('layouts.sales_report.pdf', compact('reportData', 'tables', 'month', 'year'));
            // Tambahkan $this->tableNames ke view secara eksplisit
            $pdf->setOption('tableNames', $this->tableNames);
            return $pdf->download($fileName . '.pdf');
        }
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $tables = $request->input('tables', array_keys($this->tableNames));

        $reportData = $this->getReportData($month, $year, $tables);

        return Excel::download(new SalesReportExport($reportData, $tables, $month, $year, $this->tableNames), "sales_report_{$year}_{$month}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $tables = $request->input('tables', array_keys($this->tableNames));

        $reportData = $this->getReportData($month, $year, $tables);

        $pdf = PDF::loadView('layouts.sales_report.pdf', compact('reportData', 'tables', 'month', 'year', 'tableNames'));
        return $pdf->download("sales_report_{$year}_{$month}.pdf");
    }
}
