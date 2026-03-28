<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionProvincia extends Model
{
    use HasFactory;
    protected $table = 'direccion_provincia';
    protected $primaryKey = 'idProvincia';
    public $timestamps=false;
    protected $fillable=['nombre', 'idRegion'];
}
