<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Entrada extends Model
{

    public function productos()
    {
        return $this->belongsToMany('App\Producto')->withPivot('cantidadunitaria','cantidadgramos');
    }
    public function empleado()
    {
        return $this->belongsTo('App\User');
    }
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function movimientos(){
        return $this->morphMany(Movimiento::class,'movimientoable');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
