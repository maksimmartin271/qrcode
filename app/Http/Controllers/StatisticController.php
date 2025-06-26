<?php

namespace App\Http\Controllers;

use App\Models\QrStatistic;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        // Общее количество QR-кодов
        $totalQRCodes = QrStatistic::count();

        // Распределение по типам
        $typesDistribution = QrStatistic::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // Статистика по дням
        $dailyStats = QrStatistic::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('statistics.index', compact('totalQRCodes', 'typesDistribution', 'dailyStats'));
    }
}
