<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Producto extends Model
{
    protected $with = ["tipo"];
    protected $guarded = ["id"];

    public function tipo(){
        return $this->belongsTo('App\ProductoTipo');
    }

    public function entradas()
    {
        return $this->belongsToMany('App\Entrada');
    }

    public function ventas()
    {
        return $this->belongsToMany('App\Venta');
    }

    public function retiros()
    {
        return $this->belongsToMany('App\Retiro');
    }

    public function ingresos()
    {
        return $this->belongsToMany('App\Ingreso');
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
