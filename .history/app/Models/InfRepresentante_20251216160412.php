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
    protected $fillable=['persona_id', 'parentesco', 'ocupacion'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    public function estudiantes()
    {
        return $this->hasMany(InfEstudianteRepresentante::class, 'representante_id');
    }
}
