<?php

namespace App\Http\Controllers;

use App\Producto;
use App\ProductoTipo;
use Illuminate\Http\Request;
use DB;

class ProductoController extends Controller
{
    public $validationRules = [
        'nombre' => 'required',
        'tipo_id' => 'required|integer|min:1',
        'categoria' => 'required',
        'costo' => 'nullable|integer|min:0',
        'utilidad' => 'nullable|integer|min:0',
        'precio' => 'nullable|integer|min:0',
        'stock' => 'nullable|integer|min:0',
    ];

    public $customMessages = [
        'id.required' => 'Seleccione un producto de la tabla',
        'tipo_id.required' => 'Por favor seleccione un tipo de producto'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

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

        return view("productos", compact( "tipos"));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules, $this->customMessages);
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->tipo()->associate(ProductoTipo::findOrFail($request->tipo_id));
        $producto->categoria = $request->categoria;
        $producto->costo = $request->costo == null ? 0 : $request->costo;
        $producto->utilidad = $request->utilidad == null ? 0 : $request->utilidad;
        $producto->precio = $request->precio == null ? 0 : $request->precio;
        $producto->barcode = $request->barcode;
        $producto->stock = 0;
        $producto->save();
        return response()->json([
            'msg' => '¡Producto registrado!',
        ]);
    }

    /**
     * Retrive the specified resources in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function list(){
        return datatables()->eloquent(Producto::query()->select('productos.*')->with('tipo'))->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate($this->validationIdRule, $this->customMessages);
        $request->validate($this->validationRules, $this->customMessages);
        $producto = Producto::findOrFail($request->id);
        $producto->nombre = $request->nombre;
        $producto->tipo()->associate(ProductoTipo::findOrFail($request->tipo_id));
        $producto->categoria = $request->categoria;
        $producto->costo = $request->costo == null ? 0 : $request->costo;
        $producto->utilidad = $request->utilidad == null ? 0 : $request->utilidad;
        $producto->precio = $request->precio == null ? 0 : $request->precio;
        $producto->barcode = $request->barcode;
        $producto->save();
        return response()->json([
            'msg' => '¡Producto actualizado!',
        ]);
    }

    /**
     * Remove the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate($this->validationIdRule, $this->customMessages);
        Producto::findOrFail($request->id)->delete();
        return response()->json([
            'msg' => '¡Producto eliminado!',
        ]);
    }
}
