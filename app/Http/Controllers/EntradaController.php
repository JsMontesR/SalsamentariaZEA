<?php

namespace App\Http\Controllers;

use App\Entrada;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use DB;

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

        $entrada->costo = $costo;
        $entrada->save();
        return response()->json([
            'mensaje' => 'Operación realizada',
            'descripcion' => '¡Entrada registrada!',
        ]);
    }

    /**
     * Procesa el pago de una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Entrada $entrada
     * @return \Illuminate\Http\Response
     */
    public function undoPay(Entrada $entrada)
    {
        //
    }
}
