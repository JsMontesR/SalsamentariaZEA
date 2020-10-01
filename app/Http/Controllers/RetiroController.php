<?php

namespace App\Http\Controllers;

use App\Retiro;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RetiroController extends Controller
{
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
        $request->validate($this->validationRules, $this->customMessages);
        foreach ($request->productos_venta as $producto) {
            if (empty($producto["cantidad"]) || empty($producto["costo"])) {
                throw ValidationException::withMessages(['productos_venta' => 'La tabla de productos de la venta debe contener productos con sus respectivas cantidades y costos']);
            }
        }
        if (($problem = $this->ventas->getNoDescontable($request)) != null) {
            throw ValidationException::withMessages(["productos_venta" => "No se cuenta con las existencias suficientes de " . $problem . " para realizar la venta"]);
        }

        // Ejecución de la transacción
        $this->ventas->store($request);
        return response()->json([
            'msg' => '¡Venta registrada!',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Retiro $retiro
     * @return \Illuminate\Http\Response
     */
    public function anular(Retiro $retiro)
    {
        //
    }
}
