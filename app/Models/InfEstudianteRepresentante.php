<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfEstudianteRepresentante extends Model
{
    use HasFactory;
    protected $table = 'estudiante_representante';
    protected $primaryKey = ['estudiante_id', 'representante_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'representante_id',
        'parentesco',
        'es_principal',
        'created_at',
        'updated_at',
        'viveConEstudiante'
    ];

    protected $casts = [
        
    ];

    public function estudiante()
    {
        return $this->belongsTo(InfEstudiante::class, 'estudiante_id');
    }

    public function representante()
    {
        return $this->belongsTo(InfRepresentante::class, 'representante_id');
    }
}
