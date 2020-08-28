<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoTipo extends Model
{
    const GRANEL = "Granel";
    const UNITARIO = "Unitario";
    protected $fillable = ["nombre"];
}
