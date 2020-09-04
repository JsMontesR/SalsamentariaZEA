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
     * Display a main page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("proveedores");
    }

    /**
     * Retrive a list of the resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(){
        return datatables()->eloquent(Proveedor::query())->toJson();
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
        return response()->json([
            'msg' => '¡Proveedor registrado!',
        ]);
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
        return response()->json([
            'msg' => '¡Proveedor actualizado!',
        ]);
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
        return response()->json([
            'msg' => '¡Proveedor eliminado!',
        ]);
    }
}
