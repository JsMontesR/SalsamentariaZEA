<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function productos()
    {
        return $this->belongsToMany('App\Producto');
    }
    public function empleado()
    {
        return $this->belongsTo('App\User');
    }
    public function cliente()
    {
        return $this->belongsTo('App\User');
    }

    public function movimientos(){
        return $this->morphMany(Movimiento::class,'movimientoable');
    }
}
