<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Http\Request;
use DB;

class ProveedorController extends Controller
{

    public $validationRules = [
        'nombre' => 'required',
        'telefono' => 'nullable|integer|min:0',
        'direccion' => 'nullable',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedores = DB::table('proveedors')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'),
            DB::raw('telefono as "Telefono"'),
            DB::raw('direccion as "Direccion"'),
            DB::raw('created_at as "Fecha de creación"'))->get();

        return view("proveedores", compact("proveedores"));
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
        return Proveedor::create($request->all());
        //return back()->with('success', 'Proveedor registrado registrada');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Proveedor $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Proveedor $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Proveedor $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Proveedor $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
        //
    }
}
