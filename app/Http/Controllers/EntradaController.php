<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Entrada;
use App\Exceptions\FondosInsuficientesException;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class EntradaController extends Controller
{

    public $validationRules = [
        'proveedor_id' => 'required|integer|min:1',
        'fechapago' => 'required|date',
        'productos_entrada' => 'required',
    ];

    public $customMessages = [
        'productos_entrada.required' => 'La tabla de productos de la entrada debe contener productos',
        'fechapago.required' => 'Por favor ingrese la fecha límite del pago',
        'proveedor_id.required' => 'Por favor seleccione un proveedor de la tabla'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("entradas");
    }

    /**
     * Return list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function list()
    {
        return datatables(Entrada::query()->with(['empleado', 'proveedor', 'productos']))->toJson();
    }

    /**
     * Registra una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules, $this->customMessages);
        $entrada = new Entrada();
        $entrada->fechapago = $request->fechapago;
        $entrada->proveedor()->associate(Proveedor::findOrFail($request->proveedor_id));
        $entrada->empleado()->associate(auth()->user());
        $entrada->save();
        $costo = 0;
        foreach ($request->productos_entrada as $producto) {
            if (empty($producto["cantidad"]) || empty($producto["costo"])) {
                throw ValidationException::withMessages(['productos_entrada' => 'La tabla de productos de la entrada debe contener productos con sus respectivas cantidades y costos',]);
            }
            $entrada->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $producto["costo"]]);
            $productoActual = Producto::findOrFail($producto["id"]);
            $productoActual->costo = $producto["costo"] / $producto["cantidad"];
            $precio = $productoActual->costo * (1 + $productoActual->utilidad / 100);
            $productoActual->precio = $precio;
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock + $producto["cantidad"];
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                $productoActual->stock = $productoActual->stock + ($producto["cantidad"] * 1000);
            }
            $productoActual->save();
            $costo += $producto["costo"];
        }
        $entrada->valor = $costo;
        $entrada->save();
        return response()->json([
            'msg' => '¡Entrada registrada!',
        ]);
    }

    /**
     * Procesa el pago de una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function pagar(Request $request)
    {
        $request->validate($this->validationIdRule);
        $entrada = Entrada::findOrFail($request->id);
        if ($request->parteCrediticia + $request->parteEfectiva != $entrada->valor) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar no coincide con el valor de la entrada"]);
        }
        try {
            Caja::findOrFail(1)->pagar($entrada, $request->parteEfectiva, $request->parteCrediticia);
        } catch (FondosInsuficientesException $e) {
            throw ValidationException::withMessages(["valor" => $e->getMessage()]);
        }
        return response()->json([
            'msg' => '¡Pago de entrada realizado!',
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
        $request->validate($this->validationIdRule);
        $entrada = Entrada::find($request->id);

        foreach ($entrada->productos as $producto) {
            if ($producto->stock >= $producto->pivot->cantidad) {
                $producto->stock = $producto->stock - $producto->pivot->cantidad;
                $producto->save();
            } else {
                throw ValidationException::withMessages(["id" => "No se cuenta con las existencias suficientes de " . $producto->nombre . " (" . $producto->id . ") para anular la entrada"]);
            }
        }
        if ($entrada->fechapagado != null) {
            $movimiento = $entrada->movimientos->first();
            Caja::findOrFail(1)->anularPago($entrada, $movimiento->parteEfectiva, $movimiento->parteCrediticia);
        }
        $entrada->delete();
        return response()->json([
            'msg' => '¡Pago de nomina anulado!',
        ]);
    }
}
