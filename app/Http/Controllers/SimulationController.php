<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\ProductionRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SimulationController extends Controller
{
    private const ALERT_TYPES = [
        'belt_stop' => 'Parada detectada na esteira do Posto 2',
        'low_productivity' => 'Baixa produtividade no Posto 2',
    ];

    public function detect(): JsonResponse
    {
        $count = random_int(1, 3);

        $record = ProductionRecord::create([
            'chicken_count' => $count,
            'detected_at' => now(),
        ]);

        $total = ProductionRecord::sum('chicken_count');

        Log::info('Sensor detectou frangos', [
            'count' => $count,
            'total' => $total,
            'timestamp' => $record->detected_at,
        ]);

        return response()->json([
            'success' => true,
            'chickens' => $count,
            'total' => $total,
            'detected_at' => $record->detected_at,
        ]);
    }

    public function generateAlerts(): JsonResponse
    {
        $type = array_rand(self::ALERT_TYPES);
        $message = self::ALERT_TYPES[$type];

        $alert = Alert::create([
            'type' => $type,
            'message' => $message,
            'resolved' => false,
        ]);

        Log::warning('Alerta gerado', [
            'type' => $type,
            'message' => $message,
            'alert_id' => $alert->id,
        ]);

        return response()->json([
            'success' => true,
            'alert' => [
                'id' => $alert->id,
                'type' => $alert->type,
                'message' => $alert->message,
                'created_at' => $alert->created_at,
            ],
        ]);
    }

    public function resolveAlert(Alert $alert): JsonResponse
    {
        if ($alert->resolved) {
            return response()->json([
                'success' => false,
                'message' => 'Alerta já foi resolvido anteriormente.',
            ], 409);
        }

        $alert->update(['resolved' => true]);

        Log::info('Alerta resolvido', ['alert_id' => $alert->id]);

        return response()->json(['success' => true]);
    }
}
