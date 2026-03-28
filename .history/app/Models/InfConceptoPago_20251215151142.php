<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfConceptoPago extends Model
{
    use HasFactory;
    protected $table = 'conceptospago'; // Cambia aquí el nombre de la tabla
    protected $primaryKey = 'concepto_id';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'monto',
        'recurrente',
        'periodo',
        'ano_lectivo_id',
        'nivel_id',
    ];

    public function anoLectivo()
    {
        return $this->belongsTo(InfAnoLectivo::class, 'ano_lectivo_id', 'ano_lectivo_id');
    }

    public function nivel()
    {
        return $this->belongsTo(InfNivel::class, 'nivel_id', 'nivel_id');
    }
}