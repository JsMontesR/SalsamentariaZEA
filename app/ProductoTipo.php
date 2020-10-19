<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ProductoTipo extends Model
{
    const GRANEL = "Granel";
    const UNITARIO = "Unitario";
    const GRAMOS = "gramos";
    const KILOGRAMOS = "kilogramos";
    protected $fillable = ["nombre"];

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

    public function productos(){
        return $this->hasMany('App\Producto');
    }
}
