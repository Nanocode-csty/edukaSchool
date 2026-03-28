<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfNivel extends Model
{
    protected $table = 'niveleseducativos';
    protected $primaryKey = 'nivel_id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion'];

    public function grados()
    {
        return $this->hasMany(InfGrado::class, 'nivel_id', 'nivel_id');
    }
}
