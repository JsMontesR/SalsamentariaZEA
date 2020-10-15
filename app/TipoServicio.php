<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class TipoServicio extends Model
{
    protected $fillable = ["nombre","costo"];

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

    public function servicios()
    {
        return $this->hasMany('App\Servicios');
    }
}
