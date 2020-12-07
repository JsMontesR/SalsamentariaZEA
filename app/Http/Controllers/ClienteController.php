<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use Illuminate\Http\Request;
use DB;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller
{
    public $validationRules = [
        'name' => 'required',
        'di' => 'nullable',
        'cedula' => 'nullable',
        'celular' => 'nullable|integer|min:0',
        'fijo' => 'nullable|integer|min:0',
        'email' => 'nullable|email',
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
        return view("clientes");
    }

    /**
     * Retrive the specified resources in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function list()
    {
        return datatables()->eloquent(User::query()->where('rol_id','=',3))->toJson();
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
        if ($request->email != null && User::where('email', $request->email)->exists()) {
            throw ValidationException::withMessages(['email' => 'El email ingresado ya está bajo uso de otra persona',]);
        }
        User::create($request->all() + ['rol_id' => 3]);
        return response()->json([
            'msg' => '¡Cliente registrado!',
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
        $cliente = User::findOrFail($request->id);
        if ($request->email != null && $cliente->email !=  $request->email && User::where('email', $request->email)->exists()) {
            throw ValidationException::withMessages(['email' => 'El email ingresado ya está bajo uso de otra persona',]);
        }
        $cliente->update($request->all());
        $cliente->save();
        return response()->json([
            'msg' => '¡Cliente actualizado!',
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
        $request->validate($this->validationIdRule);
        User::findOrFail($request->id)->delete();
        return response()->json([
            'msg' => '¡Cliente eliminado!',
        ]);
    }
}
