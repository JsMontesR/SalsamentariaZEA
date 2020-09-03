<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Proveedor extends Model
{
    protected $fillable = ['nombre', 'telefono', 'direccion'];

    public function entradas()
    {
        return $this->hasMany('App\Entrada');
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
