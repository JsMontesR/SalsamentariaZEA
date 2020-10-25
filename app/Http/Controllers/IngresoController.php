<?php

namespace App\Http\Controllers;

use App\Exceptions\FondosInsuficientesException;
use App\Ingreso;
use App\Repositories\Cajas;
use App\Caja;
use App\Repositories\Ingresos;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class IngresoController extends Controller
{

    public $validationRules = [
        'productos_ingreso' => 'required_without:valor',
        'valor' => 'required_without:productos_ingreso'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    protected $ingresos;
    protected $cajas;

    public function __construct(Ingresos $ingresos, Cajas $cajas)
    {
        $this->ingresos = $ingresos;
        $this->cajas = $cajas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("ingresos");
    }

    /**
     * Return list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function list()
    {
        return datatables(Ingreso::query()->with(['empleado', 'productos']))
            ->toJson();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationRules);
        foreach ($request->productos_ingreso as $producto) {
            if (empty($producto["cantidad"])) {
                throw ValidationException::withMessages(['producto_ingreso' => 'La tabla de productos del ingreso debe contener productos con sus respectivas cantidades']);
            }
        }
        // Ejecución de la transacción
        $caja = Caja::findOrFail(1);
        $ingreso = $this->ingresos->store($request);
        $this->cajas->cobrar($caja, $ingreso, $request->valor, $request->valor);
        return response()->json([
            'msg' => '¡Ingreso realizado!',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function anular(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $ingreso = Ingreso::findOrFail($request->id);
        if (($problem = $this->ingresos->getNoDescontable($ingreso)) != null) {
            throw ValidationException::withMessages(["producto_ingreso" => "No se cuenta con las existencias suficientes de " . $problem . " para realizar la devolución del ingreso"]);
        }
        $caja = Caja::findOrFail(1);
        if (!$this->cajas->isPagable($caja, $request->valor)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        // Ejecución de la transacción

        $movimiento = $ingreso->movimientos()->first();
        $this->cajas->anularCobro($movimiento);
        $this->ingresos->anular($ingreso);
        return response()->json([
            'msg' => '¡Ingreso anulado!',
        ]);
    }
}
