<?php

namespace App\Repositories;

use App\Caja;
use App\Entrada;
use App\Movimiento;

class Cajas
{

    /**
     * @param $parteEfectiva
     * @param $parteCrediticia
     * @param $valor
     * @return bool
     */
    public function isMontosPagoValidos($parteEfectiva, $parteCrediticia, $saldo)
    {
        return $parteCrediticia + $parteEfectiva <= $saldo;
    }

    /**
     * Genera un pago y su movimiento asociado
     * @param Caja $caja
     * @param $movimientoable
     * @param int $parteEfectiva
     * @param int $parteCrediticia
     */
    public function pagar(Caja $caja, $movimientoable, $parteEfectiva = 0, $parteCrediticia = 0)
    {
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::EGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo - $parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Entrada) {
            $this->actualizarSaldo($movimientoable, $nuevoMovimiento);
            if ($movimientoable->saldo == 0) {
                $movimientoable->fechapagado = now();
            };
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
    }

    /**
     * Actualiza el saldo de una entrada sin guardarlo en BD
     * @param Entrada $entrada
     */

    public function actualizarSaldo($movimientoable, $nuevoMovimiento)
    {
        $ponderado = 0;
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::EGRESO) {
                $ponderado += $movimiento->parteEfectiva + $movimiento->parteCrediticia;
            } else if ($movimiento->tipo == Movimiento::INGRESO) {
                $ponderado -= $movimiento->parteEfectiva + $movimiento->parteCrediticia;
            }
        }
        if ($nuevoMovimiento->tipo == Movimiento::EGRESO) {
            $movimientoable->saldo = $movimientoable->valor - ($ponderado + $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia);
        } else if ($nuevoMovimiento->tipo == Movimiento::INGRESO) {
            $movimientoable->saldo = $movimientoable->valor - ($ponderado - $nuevoMovimiento->parteEfectiva - $nuevoMovimiento->parteCrediticia);
        }

    }

    public function anularTodosLosPagos($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            $this->anularPago($movimiento);
        }
    }

    /**
     * Anula un pago basado en el movimiento previo, genera un nuevo movimiento
     * @param Caja $caja
     * @param $movimientoable
     * @param $parteEfectiva
     * @param $parteCrediticia
     */
    public function anularPago($movimiento, $parteEfectiva = null, $parteCrediticia = null)
    {
        $movimientoable = $movimiento->movimientoable;
        $caja = $movimiento->caja;
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? $movimiento->parteEfectiva : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? $movimiento->parteCrediticia : $parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::INGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo + $parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Entrada) {
            if ($movimientoable->saldo == 0) {
                $movimientoable->fechapagado = null;
            };
            $this->actualizarSaldo($movimientoable, $nuevoMovimiento);
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
        $movimiento->delete();

    }

    public function isPagable($caja, $parteEfectiva)
    {
        return $parteEfectiva <= $caja->saldo;
    }
}

?>
