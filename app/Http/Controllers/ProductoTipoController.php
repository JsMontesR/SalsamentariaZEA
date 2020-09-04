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
        return view("producto_tipos");
    }

    /**
     * Retrive a list of the resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(){
        return datatables()->eloquent(ProductoTipo::query())->toJson();
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
        return response()->json([
            'msg' => '¡Tipo de producto registrado!',
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
        $productoTipo = ProductoTipo::findOrFail($request->id);
        $productoTipo->update($request->all());
        $productoTipo->save();
        return response()->json([
            'msg' => '¡Tipo de producto actualizado!',
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
        ProductoTipo::findOrFail($request->id)->delete();
        return response()->json([
            'msg' => '¡Tipo de producto eliminado!',
        ]);
    }
}
