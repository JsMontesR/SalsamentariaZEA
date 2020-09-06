<?php

namespace App\Http\Controllers;

use App\Entrada;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
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
        'productos_entrada.required' => 'La tabla de productos de la entrada debe contener productos con sus respectivas cantidades y costos'
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
        return datatables(Entrada::query()->with(['empleado', 'proveedor','productos']))->toJson();
    }

    /**
     * Store a newly created resource in storage.
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
        foreach ($request->productos_entrada as $productoCoded) {
            $producto = json_decode($productoCoded);

            $entrada->productos()->attach($producto->id, ['cantidad' => $producto->cantidad, 'costo' => $producto->costo]);
            $productoActual = Producto::findOrFail($producto->id);
            $productoActual->costo = $producto->costo / $producto->cantidad;
            $precio = $productoActual->costo * (1 + $productoActual->utilidad / 100);
            $productoActual->precio = $precio;
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock + $producto->cantidad;
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                $productoActual->stock = $productoActual->stock + ($producto->cantidad * 1000);
            }
            $productoActual->save();
            $costo += $producto->costo;
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
    public function pagar(Request $request)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Entrada $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Entrada $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrada $entrada)
    {
        //
    }
}
