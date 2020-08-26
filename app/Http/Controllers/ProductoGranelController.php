<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use Illuminate\Http\Request;
use DB;

class ProductoGranelController extends Controller
{
    public $validationRules = [
        'nombre' => 'required',
        'producto_tipos_id' => 'required|integer|min:1',
        'costokilo' => 'required|integer|min:0',
        'utilidadkilo' => 'required|integer|min:0',
        'preciokilo' => 'required|integer|min:0',
        'stockgramos' => 'required|integer|min:0',
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
            DB::raw('productos.costokilo as "Costo por kilo"'),
            DB::raw('productos.utilidadkilo as "Utilidad por kilo"'),
            DB::raw('productos.preciokilo as "Precio por kilo"'),
            DB::raw('productos.stockgramos as "Gramos en stock"'),
            DB::raw('producto_tipos.id as "Id de tipo"'),
            DB::raw('producto_tipos.nombre as "Tipo de producto"'),
            DB::raw('productos.created_at as "Fecha de creación"'),
            DB::raw('productos.updated_at as "Fecha de actualización"')
        )
            ->join("producto_tipos", "productos.producto_tipos_id", "=", "producto_tipos.id")
            ->where("categoria", "Granel ")->get();

        $tipos = DB::table('producto_tipos')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'))->get();

        return view("productos_granel", compact("productos", "tipos"));
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
        Producto::create($request->all() + ['categoria' => 'Granel']);
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
