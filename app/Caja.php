<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use DateTimeInterface;

class Caja extends Model
{
    protected $fillable = ["saldo"];
    public $timestamps = false;

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

    public function ingresarDinero($cantidad)
    {
        $this->saldo = $this->saldo + $cantidad;
        $this->save();
    }

    public function sacarDinero($cantidad)
    {
        if ($cantidad > $this->saldo) {
            throw new Exception("Operación no realizable, saldo en caja insuficiente");
        } else {
            $this->saldo = $this->saldo - $cantidad;
            $this->save();
        }
    }

    public function pagar($cantidad, $auxtarjeta)
    {

    }
}
