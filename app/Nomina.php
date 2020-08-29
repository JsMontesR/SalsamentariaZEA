<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $guarded = ["id"];

    public function empleado()
    {
        return $this->belongsTo('App\User','empleado_id');
    }
}
