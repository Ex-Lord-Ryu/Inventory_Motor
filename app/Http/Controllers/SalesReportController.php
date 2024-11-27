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

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $tables = $request->input('tables', ['orderMotors', 'orderSpareParts', 'soldMotors', 'soldSpareParts']);
        $export = $request->input('export');

        // Inisialisasi $reportData dengan array kosong untuk semua tabel
        $reportData = [
            'orderMotors' => [],
            'orderSpareParts' => [],
            'soldMotors' => [],
            'soldSpareParts' => []
        ];

        // Ambil data hanya untuk tabel yang dipilih
        $selectedData = $this->getReportData($month, $year, $tables);

        // Gabungkan data yang diambil dengan array kosong yang sudah diinisialisasi
        $reportData = array_merge($reportData, $selectedData);

        if ($export) {
            return $this->export($export, $reportData, $tables, $month, $year);
        }

        return view('layouts.sales_report.index', compact('reportData', 'month', 'year', 'tables'));
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
            return Excel::download(new SalesReportExport($reportData, $tables, $month, $year), $fileName . '.xlsx');
        } elseif ($type === 'pdf') {
            $pdf = PDF::loadView('layouts.sales_report.pdf', compact('reportData', 'tables', 'month', 'year'));
            return $pdf->download($fileName . '.pdf');
        }
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $tables = $request->input('tables', ['orderMotors', 'orderSpareParts', 'soldMotors', 'soldSpareParts']);

        $reportData = $this->getReportData($month, $year, $tables);

        return Excel::download(new SalesReportExport($reportData, $tables, $month, $year), "sales_report_{$year}_{$month}.xlsx");
    }


    public function exportPdf(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $tables = $request->input('tables', ['orderMotors', 'orderSpareParts', 'soldMotors', 'soldSpareParts']);

        $reportData = $this->getReportData($month, $year, $tables);

        $pdf = PDF::loadView('layouts.sales_report.pdf', compact('reportData', 'tables', 'month', 'year'));
        return $pdf->download("layouts.sales_report_{$year}_{$month}.pdf");
    }
}
