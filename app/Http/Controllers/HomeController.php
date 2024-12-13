<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\SoldMotor;
use App\Models\OrderMotor;
use App\Models\StockMotor;
use Illuminate\Http\Request;
use App\Models\SoldSparePart;
use App\Models\OrderSparePart;
use App\Models\StockSparePart;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getData(Request $request)
    {
        try {
            $year = $request->year ?? Carbon::now()->year;
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate = Carbon::create($year, 12, 31)->endOfYear();

            $months = [];
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $months[] = $current->format('M Y');
                $current->addMonth();
            }

            $stockMotors = $this->getMonthlyData(StockMotor::class, $startDate, $endDate);
            $stockSpareParts = $this->getMonthlyData(StockSparePart::class, $startDate, $endDate);
            $orderMotors = $this->getMonthlyData(OrderMotor::class, $startDate, $endDate);
            $orderSpareParts = $this->getMonthlyData(OrderSparePart::class, $startDate, $endDate);
            $soldMotors = $this->getMonthlyData(SoldMotor::class, $startDate, $endDate, 'tanggal_terjual');
            $soldSpareParts = $this->getMonthlyData(SoldSparePart::class, $startDate, $endDate, 'tanggal_terjual');

            $data = [
                'months' => $months,
                'stock' => [
                    'motors' => $this->formatDataForApexCharts($months, $stockMotors),
                    'spareParts' => $this->formatDataForApexCharts($months, $stockSpareParts),
                ],
                'order' => [
                    'motors' => $this->formatDataForApexCharts($months, $orderMotors),
                    'spareParts' => $this->formatDataForApexCharts($months, $orderSpareParts),
                ],
                'sold' => [
                    'motors' => $this->formatDataForApexCharts($months, $soldMotors),
                    'spareParts' => $this->formatDataForApexCharts($months, $soldSpareParts),
                ],
            ];

            Log::info('Data being sent:', $data);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error in getData: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function formatDataForApexCharts($months, $data)
    {
        return array_map(function ($month) use ($data) {
            return $data[$month] ?? 0;
        }, $months);
    }

    private function getMonthlyData($modelClass, $startDate, $endDate, $dateColumn = 'created_at')
    {
        $model = new $modelClass;
        return $model::from(function ($query) use ($model, $dateColumn, $startDate, $endDate) {
            $query->selectRaw("DATE_FORMAT($dateColumn, '%b %Y') as month, COUNT(*) as count, MIN($dateColumn) as min_date")
                ->from($model->getTable())
                ->whereBetween($dateColumn, [$startDate, $endDate])
                ->groupBy('month');
        }, 'subquery')
            ->orderBy('min_date')
            ->pluck('count', 'month')
            ->toArray();
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }
}
