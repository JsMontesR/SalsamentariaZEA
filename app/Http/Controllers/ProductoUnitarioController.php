<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use DB;

class ProductoUnitarioController extends Controller
{
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
            DB::raw('producto_tipos.nombre as "Nombre del tipo"'),
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
