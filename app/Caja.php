<?php

namespace App;

use App\Exceptions\FondosInsuficientesException;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use \Datetime;

class Caja extends Model
{
    protected $fillable = ["saldo"];
    public $timestamps = false;

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

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }



}
