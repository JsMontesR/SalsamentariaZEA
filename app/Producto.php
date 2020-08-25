<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $guarded = ["id"];

    public function entradas()
    {
        return $this->belongsToMany('App\Entrada');
    }

    public function ventas()
    {
        return $this->belongsToMany('App\Venta');
    }
}
