<?php

namespace App\Http\Controllers;

use App\Entrada;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class EntradaController extends Controller
{

    public $validationRules = [
        'proveedor_id' => 'required|integer|min:1',
        'fechapago' => 'required|date',
        'productos_entrada' => 'required'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entradas = DB::table('entradas')->select(
            DB::raw('entradas.id as Id'),
            DB::raw('proveedors.id as "Id proveedor"'),
            DB::raw('proveedors.nombre as "Nombre del proveedor"'),
            DB::raw('users.name as "Empleado que registró la entrada"'),
            DB::raw('entradas.fechapago as "Fecha límite de pago"'),
            DB::raw('entradas.fechapagado as "Fecha de pago"'),
            DB::raw('entradas.costo as "Costo total de la entrada"'),
            DB::raw('entradas.created_at as "Fecha de creación"'),
            DB::raw('entradas.updated_at as "Fecha de actualización"')
        )
            ->join("users", "entradas.empleado_id", "=", "users.id")
            ->join("proveedors", "entradas.proveedor_id", "=", "proveedors.id")->get();

        $proveedors = DB::table('proveedors')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'),
            DB::raw('telefono as "Telefono"'),
            DB::raw('direccion as "Direccion"'),
            DB::raw('created_at as "Fecha de creación"'))->get();

        $productos = DB::table('productos')->select(
            DB::raw('productos.id as Id'),
            DB::raw('productos.nombre as "Nombre"'),
            DB::raw('productos.categoria as "Categoría"'),
            DB::raw('producto_tipos.nombre as "Tipo"'))
            ->join("producto_tipos", "productos.producto_tipos_id", "=", "producto_tipos.id")
            ->get();

        return view("entradas", compact("entradas", "proveedors", "productos"));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->validationRules);
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
        return back()->with('success', 'Entrada registrada');
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
