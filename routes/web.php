<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimulationController;

Route::get('/', [DashboardController::class, 'index']);

Route::prefix('simulacao')->middleware('throttle:30,1')->group(function () {
    Route::post('detectar', [SimulationController::class, 'detect']);
    Route::post('alerta', [SimulationController::class, 'generateAlerts']);
    Route::post('alerta/{alert}/resolver', [SimulationController::class, 'resolveAlert']);
});
