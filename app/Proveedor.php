<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['nombre', 'telefono', 'direccion'];

    public function entradas()
    {
        return $this->hasMany('App\Entrada');
    }
}
