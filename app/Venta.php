<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;

    protected $guarded = ["id"];

    public function productos()
    {
        return $this->belongsToMany('App\Producto')->withPivot('cantidad', 'costo');;
    }

    public function empleado()
    {
        return $this->belongsTo('App\User');
    }

    public function cliente()
    {
        return $this->belongsTo('App\User');
    }

    public function movimientos()
    {
        return $this->morphMany(Movimiento::class, 'movimientoable');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
