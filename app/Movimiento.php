<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Movimiento extends Model
{

    protected $fillable = ["parteCrediticia","parteEfectiva"];

    public function movimientoable(){
        return $this->morphTo();
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
