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
            DB::raw('created_at as "Fecha de creación"'),
            DB::raw('updated_at as "Fecha de actualización"'))->get();

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
        Proveedor::create($request->all());
        return back()->with('success', 'Proveedor registrado');
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
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->update($request->all());
        $proveedor->save();
        return back()->with('success', 'Proveedor actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate($this->validationIdRule);
        Proveedor::findOrFail($request->id)->delete();
        return back()->with('success', 'Proveedor eliminado');
    }
}
