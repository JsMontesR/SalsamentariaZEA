<?php

namespace App\Http\Controllers;

use App\ProductoTipo;
use Illuminate\Http\Request;
use DB;

class ProductoTipoController extends Controller
{

    public $validationRules = [
        'nombre' => 'required'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = DB::table('producto_tipos')->select(
            DB::raw('id as Id'),
            DB::raw('nombre as "Nombre"'))->get();

        return view("producto_tipos", compact("tipos"));
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
        ProductoTipo::create($request->all());
        return back()->with('success', 'Tipo de producto registrado');
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
        $productoTipo = ProductoTipo::findOrFail($request->id);
        $productoTipo->update($request->all());
        $productoTipo->save();
        return back()->with('success', 'Tipo de producto actualizado');
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
        ProductoTipo::findOrFail($request->id)->delete();
        return back()->with('success', 'Tipo de producto eliminado');
    }
}
