<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfPago extends Model
{
    use HasFactory;
    protected $table = 'pagos';
    protected $primaryKey = 'pago_id';
    public $timestamps = false;
    protected $fillable = [
        'matricula_id',
        'concepto_id',
        'monto',
        'fecha_vencimiento',
        'fecha_pago',
        'metodo_pago',
        'comprobante_url',
        'estado',
        'codigo_transaccion',
        'usuario_registro',
        'observaciones'
    ];

    protected $dates = [
        'fecha_vencimiento',
        'fecha_pago'
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'matricula_id');
    }

    public function concepto()
    {
        return $this->belongsTo(InfConceptoPago::class, 'concepto_id', 'concepto_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro', 'id');
    }
}
