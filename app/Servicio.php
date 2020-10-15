<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    protected $guarded = ["id"];

    use SoftDeletes;

    public function empleado()
    {
        return $this->belongsTo('App\User', 'empleado_id');
    }

    public function movimientos()
    {
        return $this->morphMany(Movimiento::class, 'movimientoable');
    }

    public function tipoServicio(){
        return $this->belongsTo('App\TipoServicio');
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
