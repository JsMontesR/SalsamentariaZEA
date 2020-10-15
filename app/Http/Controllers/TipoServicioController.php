<?php

namespace App\Http\Controllers;

use App\ProductoTipo;
use App\TipoServicio;
use Illuminate\Http\Request;
use DB;

class TipoServicioController extends Controller
{

    public $validationRules = [
        'nombre' => 'required',
        'costo' => 'required|integer|min:0',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("tipo_servicios");
    }

    /**
     * Retrive a list of the resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(){
        return datatables()->eloquent(TipoServicio::query())->toJson();
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
        TipoServicio::create($request->all());
        return response()->json([
            'msg' => '¡Tipo de servicio registrado!',
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
        $tiposervicio = TipoServicio::findOrFail($request->id);
        $tiposervicio->update($request->all());
        $tiposervicio->save();
        return response()->json([
            'msg' => '¡Tipo de servicio actualizado!',
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
        TipoServicio::findOrFail($request->id)->delete();
        return response()->json([
            'msg' => '¡Tipo de servicio eliminado!',
        ]);
    }
}
