<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use Illuminate\Http\Request;
use DB;

class ProductoUnitarioController extends Controller
{
    public $validationRules = [
        'nombre' => 'required',
        'producto_tipos_id' => 'required|integer|min:1',
        'costounitario' => 'required|integer|min:0',
        'utilidadunitaria' => 'required|integer|min:0',
        'preciounitario' => 'required|integer|min:0',
        'stockunitario' => 'required|integer|min:0',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = DB::table('productos')->select(
            DB::raw('productos.id as Id'),
            DB::raw('productos.nombre as "Nombre"'),
            DB::raw('productos.costounitario as "Costo unitario"'),
            DB::raw('productos.utilidadunitaria as "Utilidad unitaria"'),
            DB::raw('productos.preciounitario as "Precio unitario"'),
            DB::raw('productos.stockunitario as "Unidades en stock"'),
            DB::raw('producto_tipos.id as "Id de tipo"'),
            DB::raw('producto_tipos.nombre as "Tipo de producto"'),
            DB::raw('productos.created_at as "Fecha de creación"'),
            DB::raw('productos.updated_at as "Fecha de actualización"')
        )
            ->join("producto_tipos", "productos.producto_tipos_id", "=", "producto_tipos.id")
            ->where("categoria", "UNITARIO")->get();

        $tipos = DB::table('producto_tipos')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'))->get();

        return view("productos_unitarios", compact("productos", "tipos"));
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
        Producto::create($request->all() + ['categoria' => 'UNITARIO']);
        return back()->with('success', 'Producto registrado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate($this->validationIdRule);
        $request->validate($this->validationRules);
        $producto = Producto::findOrFail($request->id);
        $producto->update($request->all());
        $producto->save();
        return back()->with('success', 'Producto actualizado');
    }

    /**
     * Remove the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate($this->validationIdRule);
        Producto::findOrFail($request->id)->delete();
        return back()->with('success', 'Producto eliminado');
    }
}
