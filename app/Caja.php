<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;


class Caja extends Model
{
    protected $fillable = ["saldo"];
    public $timestamps = false;

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
