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
    public function isMontosPagoValidos($parteEfectiva, $parteCrediticia, $valor)
    {
        return $parteCrediticia + $parteEfectiva <= $valor;
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
        $caja->saldo = $caja->saldo - $parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Entrada && $this->isDeudaAPaz($movimientoable, $nuevoMovimiento)) {
            $movimientoable->fechapagado = now();
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
    }

    public function isDeudaAPaz($movimientoable, Movimiento $nuevoMovimiento)
    {
        $ponderado = 0;
        foreach ($movimientoable->movimientos as $movimiento) {
            $ponderado += $movimiento->parteEfectiva + $movimiento->parteCrediticia;
        }
        return $ponderado + $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia == $movimientoable->valor;
    }

    /**
     * Anula un pago basado en el movimiento previo, genera un nuevo movimiento
     * @param Caja $caja
     * @param $movimientoable
     * @param $parteEfectiva
     * @param $parteCrediticia
     */
    public function anularPago(Caja $caja, $movimientoable, $parteEfectiva, $parteCrediticia)
    {
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
        $nuevoMovimiento->tipo = Movimiento::INGRESO;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
        $caja->saldo = $caja->saldo + $parteEfectiva;
        $caja->save();
        $caja->refresh();
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
    }

    public function isPagable($caja, $parteEfectiva)
    {
        return $parteEfectiva <= $caja->saldo;
    }
}

?>
