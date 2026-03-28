<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    protected $table = 'comunicados';
    protected $primaryKey = 'idComunicado';
    public $timestamps = false;
    protected $fillable = ['descripcion', 'flyer_url', 'fecha_inicio', 'fecha_fin', 'publico'];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
}
