<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionDistrito extends Model
{
    use HasFactory;
    protected $table = 'direccion_distrito';
    protected $primaryKey = 'idDistrito';
    public $timestamps=false;
    protected $fillable=['nombre', 'idProvincia'];
}
