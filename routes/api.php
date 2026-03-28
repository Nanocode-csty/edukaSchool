<?php

use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/mercadopago', [MercadoPagoController::class, 'webhook']);
