<?php

namespace App\Repositories;

use App\Caja;
use App\Entrada;
use App\Movimiento;
use App\Nomina;
use App\Venta;
use Illuminate\Support\Facades\Log;

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
        if ($movimientoable instanceof Entrada || $movimientoable instanceof Nomina) {
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
     * Genera un cobro y su movimiento asociado
     * @param Caja $caja
     * @param $movimientoable
     * @param int $parteEfectiva
     * @param int $parteCrediticia
     */
    public function cobrar(Caja $caja, $movimientoable, $parteEfectiva = 0, $parteCrediticia = 0)
    {
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::INGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo + $parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Venta) {
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
     * Anula un cobro basado en el movimiento previo, genera un nuevo movimiento
     * @param Caja $caja
     * @param $movimientoable
     * @param $parteEfectiva
     * @param $parteCrediticia
     */
    public function anularCobro($movimiento, $parteEfectiva = null, $parteCrediticia = null)
    {
        $movimientoable = $movimiento->movimientoable;
        $caja = $movimiento->caja;
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? $movimiento->parteEfectiva : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? $movimiento->parteCrediticia : $parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::EGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo - $nuevoMovimiento->parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Venta) {
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


    /**
     * Actualiza el saldo de una entrada sin guardarlo en BD
     * @param Entrada $entrada
     */

    public function actualizarSaldo($movimientoable, $nuevoMovimiento)
    {
        if ($movimientoable instanceof Venta) {
            if ($nuevoMovimiento->tipo == Movimiento::EGRESO) {
                $movimientoable->saldo += $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            } else if ($nuevoMovimiento->tipo == Movimiento::INGRESO) {
                $movimientoable->saldo -= $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            }
        } else if ($movimientoable instanceof Entrada || $movimientoable instanceof Nomina) {
            if ($nuevoMovimiento->tipo == Movimiento::EGRESO) {
                $movimientoable->saldo -= $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            } else if ($nuevoMovimiento->tipo == Movimiento::INGRESO) {
                $movimientoable->saldo += $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            }
        }
    }

    public function anularTodosLosPagos($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::EGRESO)
                $this->anularPago($movimiento);
        }
    }

    public function anularTodosLosCobros($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::INGRESO)
                $this->anularCobro($movimiento);
        }
    }

    public function getCobroNoAnulable($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::INGRESO && !$this->isPagable($movimiento->caja, $movimiento->parteEfectiva)) {
                return "el cobro # " . $movimiento->id . " por un monto de " . $movimiento->parteEfectiva;
            }
        }
        return null;
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
        $caja->saldo = $caja->saldo + $nuevoMovimiento->parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Entrada || $movimientoable instanceof Nomina) {
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
