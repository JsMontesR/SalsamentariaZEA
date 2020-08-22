<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use DB;

class ProductoGranelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = DB::table('productos')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'),
            DB::raw('costokilo as "Costo por kilo"'),
            DB::raw('utilidadkilo as "Utilidad pr kilo"'),
            DB::raw('preciokilo as "Precio por kilo"'),
            DB::raw('stockkilo as "Kilos en stock"'),
            DB::raw('created_at as "Fecha de creación"'))->where("categoria","GRANEL")->get();

        $tipos = DB::table('producto_tipos')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'))->get();

        return view("productos", compact("productos", "tipos"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
