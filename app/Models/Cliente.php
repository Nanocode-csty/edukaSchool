<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'cliente';
    protected $primaryKey = 'dniCliente';
    public $timestamps=false;
    protected $fillable=['apellido_paterno','apellido_materno','nombres','estado'];
}
