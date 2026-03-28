<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionPersona extends Model
{
    use HasFactory;
    protected $table = 'direccion_persona';
    protected $primaryKey = 'idDireccion';
    public $timestamps = false;

    protected $fillable = [
        'idDireccion',
        'idPersona',
        'idRegion',
        'idProvincia',
        'idDistrito',
        'nombreAvenida',
        'referencia',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'idDireccion' => 'integer',
        'idPersona' => 'integer',
        'idRegion' => 'integer',
        'idProvincia' => 'integer',
        'idDistrito' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function region()
    {
        return $this->belongsTo(
            DireccionRegion::class,
            'idRegion',
            'idRegion'
        );
    }

    public function provincia()
    {
        return $this->belongsTo(
            DireccionProvincia::class,
            'idProvincia',
            'idProvincia'
        );
    }

    public function distrito()
    {
        return $this->belongsTo(
            DireccionDistrito::class,
            'idDistrito',
            'idDistrito'
        );
    }
}
