<?php

namespace App\Http\Controllers;

use App\Exceptions\FondosInsuficientesException;
use App\Caja;
use App\Movimiento;
use App\Nomina;
use App\Venta;
use App\Entrada;
use App\Repositories\Cajas;
use App\Repositories\Entradas;
use App\Repositories\Nominas;
use App\Repositories\Ventas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class MovimientoController extends Controller
{
    protected $cajas, $ventas, $nominas, $entradas;

    public function __construct(Cajas $cajas, Ventas $ventas, Nominas $nominas, Entradas $entradas)
    {
        $this->ventas = $ventas;
        $this->nominas = $nominas;
        $this->entradas = $entradas;
        $this->cajas = $cajas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('movimientos');
    }

    /**
     * Genera un pago.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generarPago(Request $request)
    {
        $tipo = $request->movimientoable;
        $caja = Caja::findOrFail(1);
        switch ($tipo) {
            case "entrada":
                $movimientoable = Entrada::findOrFail($request->id);
                $repository = $this->entradas;
            case "nomina":
                $movimientoable = Nomina::findOrFail($request->id);
                $repository = $this->nominas;
            default:
                throw ValidationException::withMessages(["movimientoable" => "El tipo de operación a pagar es indefinido"]);
        }

        if (!$repository->isProcesable($movimientoable)) {
            throw ValidationException::withMessages(["valor" => "La " . $tipo . " seleccionada ya fue pagada en su totalidad"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $movimientoable->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        if (!$this->cajas->isPagable($caja, $request->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        $this->cajas->pagar($caja, $movimientoable, $request->parteEfectiva, $request->parteCrediticia);
        return response()->json([
            'msg' => '¡Pago de ' . $tipo . ' realizado!',
        ]);
    }

    /**
     * Anula un pago.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function anularPago(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $movimiento = Movimiento::findOrFail($request->id);

        // Ejecución de la transacción
        $this->cajas->anularPago($movimiento);
        return response()->json([
            'msg' => '¡Pago anulado!',
        ]);
    }

    /**
     * Genera un cobro.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generarCobro(Request $request)
    {
        $tipo = $request->movimientoable;
        $caja = Caja::findOrFail(1);
        switch ($tipo) {
            case "venta":
                $movimientoable = Venta::findOrFail($request->id);
                $repository = $this->ventas;
                break;
            default:
                throw ValidationException::withMessages(["movimientoable" => "El tipo de operación a cobrar es indefinido"]);
        }
        if (!$this->cajas->isProcesable($movimientoable)) {
            throw ValidationException::withMessages(["valor" => "La " . $tipo . " seleccionada ya fue pagada en su totalidad"]);
        }
        if ($this->cajas->generarCambio($request->parteEfectiva, $request->efectivoRecibido) < 0) {
            throw ValidationException::withMessages(["valor" => "El efectivo recibido es menor al valor efectivo a cobrar"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $movimientoable->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        $this->cajas->cobrar($caja, $movimientoable, $request->efectivoRecibido ,$request->parteEfectiva, $request->parteCrediticia);
        return response()->json([
            'msg' => '¡Pago de ' . $tipo . ' realizado!',
        ]);
    }

    /**
     * Anula un cobro.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function anularCobro(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $movimiento = Movimiento::findOrFail($request->id);
        if (!$this->cajas->isPagable($movimiento->caja, $movimiento->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["id" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        // Ejecución de la transacción
        $this->cajas->anularCobro($movimiento);
        return response()->json([
            'msg' => '¡Cobro anulado!',
        ]);
    }


    /**
     * Retrive a list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return datatables()->eloquent(Movimiento::query()->with('empleado')->withTrashed()
        )->addColumn('valor', function (Movimiento $movimiento) {
            return $movimiento->movimientoable->valor;
        })->filterColumn('valor', function ($query, $keyword) {
            $movimientos = Movimiento::whereHasMorph('movimientoable', "*", function ($subquery) use ($keyword) {
                $subquery->where('valor', 'like', '%' . $keyword . '%')->withTrashed();
            })->get()->pluck('id')->toArray();
            $query->whereIn('id', $movimientos);
        })->toJson();
    }

}
