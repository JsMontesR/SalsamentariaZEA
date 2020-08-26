<?php

namespace App\Http\Controllers;

use App\Entrada;
use Illuminate\Http\Request;
use DB;

class EntradaController extends Controller
{
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Entrada $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Entrada $entrada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Entrada $entrada
     * @return \Illuminate\Http\Response
     */
    public function edit(Entrada $entrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Entrada $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrada $entrada)
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
