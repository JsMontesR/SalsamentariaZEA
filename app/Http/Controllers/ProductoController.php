<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use DB;

class ProductoController extends Controller
{
    public $validationRules = [
        'nombre' => 'required',
        'producto_tipos_id' => 'required|integer|min:1',
        'categoria' => 'required',
        'costo' => 'nullable|integer|min:0',
        'utilidad' => 'nullable|integer|min:0',
        'precio' => 'nullable|integer|min:0',
        'stock' => 'nullable|integer|min:0',
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
            DB::raw('productos.categoria as "Categoría"'),
            DB::raw('productos.costo as "Costo Unitario/Kg"'),
            DB::raw('productos.utilidad as "Utilidad Unitaria/Kg"'),
            DB::raw('productos.precio as "Precio Unitario/Kg"'),
            DB::raw('productos.stock as "Unidades/g en stock"'),
            DB::raw('producto_tipos.id as "Id de tipo"'),
            DB::raw('producto_tipos.nombre as "Tipo de producto"'),
            DB::raw('productos.created_at as "Fecha de creación"'),
            DB::raw('productos.updated_at as "Fecha de actualización"')
        )
            ->join("producto_tipos", "productos.producto_tipos_id", "=", "producto_tipos.id")->get();

        $tipos = DB::table('producto_tipos')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'))->get();

        return view("productos", compact("productos", "tipos"));
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
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->producto_tipos_id = $request->producto_tipos_id;
        $producto->categoria = $request->categoria;
        $producto->costo = $request->costo == null ? 0 : $request->costo;
        $producto->utilidad = $request->utilidad == null ? 0 : $request->utilidad;
        $producto->precio = $request->precio == null ? 0 : $request->precio;
        $producto->stock = $request->stock == null ? 0 : $request->stock;
        $producto->save();
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
        $producto->nombre = $request->nombre;
        $producto->producto_tipos_id = $request->producto_tipos_id;
        $producto->categoria = $request->categoria;
        $producto->costo = $request->costo == null ? 0 : $request->costo;
        $producto->utilidad = $request->utilidad == null ? 0 : $request->utilidad;
        $producto->precio = $request->precio == null ? 0 : $request->precio;
        $producto->stock = $request->stock == null ? 0 : $request->stock;
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
