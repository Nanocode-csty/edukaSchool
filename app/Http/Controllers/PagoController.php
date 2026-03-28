<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\InfPago;

use App\Models\Matricula;
use Carbon\Carbon;

class PagoController extends Controller
{
    // Mostrar página con el botón "Pagar"
    public function mostrarPago($id_pago)
    {
        $pago = InfPago::where('id_pago', $id_pago)->firstOrFail();

        if (in_array($pago->estado, ['Pagado', 'PAGADO', 'Matriculado'])) {
            return redirect()->back()->with('info', 'Este pago ya está registrado como pagado.');
        }

        return view('pagos.pagar', compact('pago'));
    }

    // 👉 Procesar pago con Culqi
    public function procesarPago(Request $request)
    {
        $request->validate([
            'pago_id' => 'required|exists:inf_pagos,id_pago',
            'token'   => 'required|string',
            'email'   => 'nullable|email'
        ]);

        $pago = InfPago::where('id_pago', $request->pago_id)->firstOrFail();

        if (in_array($pago->estado, ['Pagado', 'PAGADO'])) {
            return response()->json(['success' => false, 'message' => 'Esta orden ya está pagada.']);
        }

        try {
            DB::beginTransaction();

            $amount_cents = intval(round($pago->monto * 100));
            $secret = config('services.culqi.secret_key') ?? env('CULQI_SECRET_KEY');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secret,
                'Content-Type'  => 'application/json',
            ])->post('https://api.culqi.com/v2/charges', [
                'amount'        => $amount_cents,
                'currency_code' => $pago->moneda ?? 'PEN',
                'email'         => $request->email ?? ($pago->email ?? 'no-reply@tu-dominio.com'),
                'source_id'     => $request->token,
                "metadata"      => [
                    "pago_id"      => $pago->id_pago,
                    "matricula_id" => $pago->matricula_id
                ],
            ]);

            $data = $response->json();
            Log::info('Culqi charge response: ' . json_encode($data));

            $isSuccess = false;
            if (isset($data['id']) && isset($data['status']) && in_array(strtolower($data['status']), ['captured', 'succeeded', 'success'])) {
                $isSuccess = true;
            }
            if (!$isSuccess && isset($data['outcome']['type']) && stripos($data['outcome']['type'], 'venta') !== false) {
                $isSuccess = true;
            }

            if ($response->successful() && $isSuccess) {
                $pago->codigo_transaccion = $data['id'] ?? null;
                $pago->metodo_pago        = 'Tarjeta';
                $pago->fecha_pago         = Carbon::now();
                $pago->estado             = 'Pagado';
                $pago->save();

                if ($pago->matricula_id) {
                    $mat = Matricula::where('matricula_id', $pago->matricula_id)->first();
                    if ($mat) {
                        $mat->estado = 'Matriculado';
                        $mat->save();
                    }
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Pago realizado con éxito.']);
            }

            $pago->estado        = 'Rechazado';
            $pago->observaciones = json_encode($data);
            $pago->save();

            DB::commit();
            return response()->json(['success' => false, 'message' => 'Pago rechazado por la pasarela.', 'culqi' => $data], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error procesarPago: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    public function validarPago(Request $request)
    {


        $pago = InfPago::where('pago_id', $request->pago_id)->firstOrFail();

        // Evitar validación si ya está pagado
        if (in_array($pago->estado, ['Pagado', 'PAGADO'])) {
            return response()->json([
                'success' => false,
                'message' => 'Esta orden ya está pagada.'
            ]);
        }

        try {
            DB::beginTransaction();

            // 👉 Generar código de transacción de 6 dígitos aleatorios
            $pago->codigo_transaccion = mt_rand(100000, 999999);
            $pago->metodo_pago = 'Efectivo';
            $pago->fecha_pago = Carbon::now();
            $pago->estado = 'Pagado';
            $pago->observaciones = 'Pago validado manualmente por el sistema.';
            $pago->save();

            // Actualizar matrícula si aplica
            if ($pago->matricula_id) {
                $mat = Matricula::where('matricula_id', $pago->matricula_id)->first();
                if ($mat) {
                    $mat->estado = 'Matriculado';
                    $mat->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago validado con éxito.',
                //'codigo' => $pago->codigo_transaccion // 👉 opcional, puedes mostrarlo en el front
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error validarPago: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }




    // 👉 Webhook Culqi (opcional)
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Webhook Culqi payload: ' . json_encode($payload));

        $type   = $payload['type'] ?? null;
        $object = $payload['data']['object'] ?? null;

        if ($type === 'charge.succeeded' && $object) {
            $chargeId = $object['id'] ?? null;

            if ($chargeId) {
                $pago = InfPago::where('codigo_transaccion', $chargeId)->first();
                if ($pago) {
                    $pago->estado     = 'Pagado';
                    $pago->fecha_pago = Carbon::now();
                    $pago->save();

                    if ($pago->matricula_id) {
                        $mat = Matricula::where('matricula_id', $pago->matricula_id)->first();
                        if ($mat) {
                            $mat->estado = 'Matriculado';
                            $mat->save();
                        }
                    }
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
