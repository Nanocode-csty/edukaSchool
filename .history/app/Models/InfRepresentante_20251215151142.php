<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfRepresentante extends Model
{
    use HasFactory;
    protected $table = 'representantes';
    protected $primaryKey = 'representante_id';
    public $timestamps=false;
    protected $fillable=['dni','nombres','apellidoPaterno','apellidoMaterno','parentesco','telefono','telefono_alternativo','email','direccion','ocupacion','fecha_registro'];

    public function estudiantes()
    {
        return $this->hasMany(InfEstudianteRepresentante::class, 'representante_id');
    }
}
