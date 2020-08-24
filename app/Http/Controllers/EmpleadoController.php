<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmpleadoController extends Controller
{
    public $validationRules = [
        'name' => 'required',
        'di' => 'nullable',
        'cedula' => 'nullable|integer|min:0',
        'celular' => 'nullable|integer|min:0',
        'fijo' => 'nullable|integer|min:0',
        'email' => 'nullable|email',
        'password' => 'required|min:8',
        'rol_id' => 'required|integer|min:1',
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
        $empleados = DB::table('users')->select(
            DB::raw('users.id as Id'),
            DB::raw('users.name as "Nombre"'),
            DB::raw('users.di as "Documento de identidad"'),
            DB::raw('users.celular as "Teléfono celular"'),
            DB::raw('users.fijo as "Teléfono fijo"'),
            DB::raw('users.email as "Correo electrónico"'),
            DB::raw('users.direccion as "Dirección"'),
            DB::raw('users.salario as "Salario"'),
            DB::raw('rols.id as "Id de rol"'),
            DB::raw('rols.nombre as "Rol"'),
            DB::raw('users.created_at as "Fecha de creación"'),
            DB::raw('users.updated_at as "Fecha de actualización"')
        )
            ->join("rols", "users.rol_id", "=", "rols.id")
            ->where("rols.nombre", "<>", "cliente")->get();

        $rols = DB::table('rols')->select(
            DB::raw('id as "Id de rol"'),
            DB::raw('nombre as "Rol"')
        )->where("nombre", "<>", "cliente")->get();

        return view("empleados", compact("empleados", "rols"));
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
        if ($request->email != null && DB::table('users')->where('email', '=', $request->email)->exists()) {
            throw ValidationException::withMessages(['email' => 'El email ingresado ya está bajo uso de otra persona',]);
        }
        if ($request->password != null) {
            $request->merge([
                'password' => Hash::make($request->password),
            ]);
        }
        User::create($request->all());
        return back()->with('success', 'Empleado registrado');
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
        $empleado = User::findOrFail($request->id);
        if ($request->email != null && $empleado->email !=  $request->email && DB::table('users')->where('email', '=', $request->email)->exists()) {
            throw ValidationException::withMessages(['email' => 'El email ingresado ya está bajo uso de otra persona',]);
        }
        if ($request->password != null) {
            $request->password = Hash::make($request->password);
        }

        $empleado->update($request->all());
        $empleado->save();
        return back()->with('success', 'Empleado actualizado');
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
        return back()->with('success', 'Empleado eliminado');
    }
}
