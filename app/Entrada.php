<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    public function productos()
    {
        return $this->belongsToMany('App\Producto');
    }
    public function empleado()
    {
        return $this->belongsTo('App\User');
    }
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
}
