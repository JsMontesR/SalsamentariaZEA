<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use Illuminate\Http\Request;
use DB;

class ClienteController extends Controller
{
    public $validationRules = [
        'name' => 'required',
        'di' => 'nullable|email',
        'cedula' => 'nullable|integer|min:0',
        'celular' => 'nullable|integer|min:0',
        'fijo' => 'nullable|integer|min:0',
        'direccion' => 'nullable',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = DB::table('users')->select(
            DB::raw('users.id as Id'),
            DB::raw('users.nombre as "Nombre"'),
            DB::raw('users.di as "Documento de identidad"'),
            DB::raw('users.celular as "Teléfono celular"'),
            DB::raw('users.fijo as "Teléfono fijo"'),
            DB::raw('users.direccion as "Dirección"'),
            DB::raw('rols.id as "Id de rol"'),
            DB::raw('rols.nombre as "Rol"'),
            DB::raw('users.created_at as "Fecha de creación"'),
            DB::raw('users.updated_at as "Fecha de actualización"')
        )
            ->join("rols", "users.rol_id", "=", "rols.id")
            ->where("rols.nombre", "cliente")->get();

        return view("clientes", compact("productos", "tipos"));
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
        User::create($request->all());
        return back()->with('success', 'Cliente registrado');
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
        $cliente = User::findOrFail($request->id);
        $cliente->update($request->all());
        $cliente->save();
        return back()->with('success', 'Cliente actualizado');
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
        User::findOrFail($request->id)->delete();
        return back()->with('success', 'Cliente eliminado');
    }
}
