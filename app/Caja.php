<?php

namespace App;

use App\Exceptions\FondosInsuficientesException;
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
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    public function pagarNomina($movimientoable, $parteEfectiva = 0, $parteCrediticia = 0)
    {
        if ($parteEfectiva > $this->saldo) {
            throw new FondosInsuficientesException("Operación no realizable, saldo en caja insuficiente");
        } else {
            $nuevoMovimiento = new Movimiento();
            $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
            $nuevoMovimiento->ingreso = Movimiento::EGRESO;
            $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
            $this->saldo = $this->saldo - $parteEfectiva;
            $this->save();
            $this->refresh();
            $movimientoable->save();
            $movimientoable->refresh();
            $nuevoMovimiento->caja()->associate($this);
            $nuevoMovimiento->movimientoable()->associate($movimientoable);
            $nuevoMovimiento->save();
        }
    }

    public function anularNomina($movimientoable, $parteEfectiva, $parteCrediticia)
    {
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
        $nuevoMovimiento->ingreso = Movimiento::INGRESO;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
        $this->saldo = $this->saldo + $parteEfectiva;
        $this->save();
        $this->refresh();
        $nuevoMovimiento->caja()->associate($this);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
    }

}
