<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\ProductionRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $totalChickens = ProductionRecord::sum('chicken_count');

        $todayChickens = ProductionRecord::whereDate('detected_at', today())->sum('chicken_count');
        $lastRecord = ProductionRecord::latest('detected_at')->first();
        $activeAlerts = Alert::where('resolved', false)->get();

        $productionRate = 0;
        if ($lastRecord) {
            $fiveMinAgo = now()->subMinutes(5);
            $recentCount = ProductionRecord::where('detected_at', '>=', $fiveMinAgo)->sum('chicken_count');
            $productionRate = round($recentCount / 5, 1);
        }

        $recentRecords = ProductionRecord::orderBy('detected_at', 'desc')->take(20)->get();

        return view('dashboard', compact(
            'totalChickens', 'todayChickens', 'lastRecord',
            'activeAlerts', 'productionRate', 'recentRecords'
        ));
    }
}
