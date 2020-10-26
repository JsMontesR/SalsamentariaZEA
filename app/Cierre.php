<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Cierre extends Model
{
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

    public function caja()
    {
        return $this->belongsTo('App\Caja', 'caja_id');
    }

    public function cierreAnterior()
    {
        return $this->belongsTo('App\Cierre', 'cierre_id');
    }

}
