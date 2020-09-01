<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{

    protected $fillable = ["parteCrediticia","parteEfectiva"];

    public function movimientoable(){
        return $this->morphTo();
    }
}
