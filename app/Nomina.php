<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $guarded = ["id"];

    public function empleado()
    {
        return $this->belongsTo('App\User','empleado_id');
    }

    public function movimientos(){
        return $this->morphMany(Movimiento::class,'movimientoable');
    }
}
