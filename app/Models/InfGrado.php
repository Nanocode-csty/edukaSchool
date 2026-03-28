<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfGrado extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'grado_id';
    public $timestamps = false;
    protected $fillable = ['nivel_id', 'nombre', 'descripcion'];
    
    // Relation with educational level
    public function nivel()
    {
        return $this->belongsTo(InfNivel::class, 'nivel_id', 'nivel_id');
    }

    public function cursos()
    {
        return $this->hasMany(InfCurso::class, 'grado_id', 'grado_id');
    }
    

}