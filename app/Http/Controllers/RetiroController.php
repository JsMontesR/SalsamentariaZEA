<?php

namespace App\Http\Controllers;

use App\Exceptions\FondosInsuficientesException;
use App\Repositories\Cajas;
use App\Repositories\Retiros;
use App\Retiro;
use App\Caja;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RetiroController extends Controller
{

    public $validationRules = [
        'productos_retiro' => 'required_without:valor',
        'valor' => 'required_without:productos_retiro'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    protected $retiros;
    protected $cajas;

    public function __construct(Retiros $retiros, Cajas $cajas)
    {
        $this->retiros = $retiros;
        $this->cajas = $cajas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("retiros");
    }

    /**
     * Return list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function list()
    {
        return datatables(Retiro::query()->with(['empleado', 'productos']))
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
        foreach ($request->productos_retiro as $producto) {
            if (empty($producto["cantidad"])) {
                throw ValidationException::withMessages(['producto_retiro' => 'La tabla de productos del retiro debe contener productos con sus respectivas cantidades']);
            }
        }
        if (($problem = $this->retiros->getNoDescontable($request)) != null) {
            throw ValidationException::withMessages(["producto_retiro" => "No se cuenta con las existencias suficientes de " . $problem . " para realizar el retiro"]);
        }
        $caja = Caja::findOrFail(1);
        if (!$this->cajas->isPagable($caja, $request->valor)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        // Ejecución de la transacción
        $retiro = $this->retiros->store($request);
        $this->cajas->pagar($caja, $retiro, $request->valor);
        return response()->json([
            'msg' => '¡Retiro registrado!',
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
        // Ejecución de la transacción
        $retiro = Retiro::findOrFail($request->id);
        $movimiento = $retiro->movimientos()->first();
        $this->cajas->anularPago($movimiento);
        $this->retiros->anular($retiro);
        return response()->json([
            'msg' => '¡Retiro anulado!',
        ]);
    }
}
