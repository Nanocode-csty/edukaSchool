<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    use HasFactory;

    protected $table = 'competencias';
    protected $primaryKey = 'competencia_id';
    public $timestamps = false;

    protected $fillable = [
        'asignatura_id',
        'nombre',
        'descripcion',
        'orden'
    ];

    public function asignatura()
    {
        return $this->belongsTo(InfAsignatura::class, 'asignatura_id', 'asignatura_id');
    }
}
