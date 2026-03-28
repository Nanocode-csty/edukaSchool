<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionRegion extends Model
{
    use HasFactory;
    protected $table = 'direccion_region';
    protected $primaryKey = 'idRegion';
    public $timestamps=false;
    protected $fillable=['nombre'];
}
