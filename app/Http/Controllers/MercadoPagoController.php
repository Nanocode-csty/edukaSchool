<?php

namespace App\Http\Controllers;

use App\Models\InfPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pago;
use App\Models\Matricula;

class MercadoPagoController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('Webhook recibido de Mercado Pago:', $request->all());

        // Obtenemos el ID del pago enviado por Mercado Pago
        $paymentId = $request->input('data.id');

        if (!$paymentId) {
            Log::warning('Webhook sin ID de pago.');
            return response()->json(['ok' => false, 'message' => 'Sin ID de pago'], 400);
        }

        // Buscamos el pago pendiente relacionado con el código de transacción
        $pago = InfPago::where('codigo_transaccion', $paymentId)
                    ->where('estado', 'Pendiente')
                    ->first();

        if (!$pago) {
            Log::info("Pago {$paymentId} ya procesado o no existe.");
            return response()->json(['ok' => true, 'message' => 'Pago ya procesado o no encontrado'], 200);
        }

        // Actualizamos los datos del pago
        $pago->fecha_pago = now();
        $pago->metodo_pago = 'MercadoPago';
        $pago->estado = 'Pagado';
        $pago->save();

        // Actualizamos el estado de la matrícula
        $matricula = Matricula::find($pago->matricula_id);
        if ($matricula && $matricula->estado !== 'Matriculado') {
            $matricula->estado = 'Matriculado';
            $matricula->save();
        }

        Log::info("Pago {$paymentId} confirmado y matrícula {$pago->matricula_id} actualizada.");

        return response()->json(['ok' => true], 200);
    }
}
