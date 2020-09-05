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
        'email' => 'required|email',
        'rol_id' => 'required|integer|min:1',
        'direccion' => 'nullable',
    ];

    public $passwordValidationRule = [
        'password' => 'required|min:8',
    ];

    public $customMessages = [
        'id.required' => 'Seleccione un empleado de la tabla',
        'rol_id.required' => 'Por favor seleccione un rol para el empleado'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $rols = DB::table('rols')->select(
            DB::raw('id as "Id de rol"'),
            DB::raw('nombre as "Rol"')
        )->where("nombre", "<>", "cliente")->get();

        return view("empleados", compact("rols"));
    }


    /**
     * Retrive the specified resources in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function list()
    {
        return datatables()->eloquent(User::query()->with('rol')->where('rol_id','<>',3))->toJson();
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
        $request->validate($this->passwordValidationRule);
        if ($request->email != null && User::where('email', $request->email)->exists()) {
            throw ValidationException::withMessages(['email' => 'El email ingresado ya está bajo uso de otra persona',]);
        }
        if ($request->password != null) {
            $request->merge([
                'password' => Hash::make($request->password),
            ]);
        }
        User::create($request->all());
        return response()->json([
            'msg' => '¡Empleado registrado!',
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
        $request->validate($this->validationIdRule, $this->customMessages);
        $request->validate($this->validationRules, $this->customMessages);
        $empleado = User::findOrFail($request->id);
        if ($request->email != null && $empleado->email != $request->email && User::where('email', $request->email)->exists()) {
            throw ValidationException::withMessages(['email' => 'El email ingresado ya está bajo uso de otra persona',]);
        }
        if ($request->password != null) {
            $request->merge([
                'password' => Hash::make($request->password),
            ]);
        } else {
            $request->request->remove('password');
        }

        $empleado->update($request->all());
        $empleado->save();
        return response()->json([
            'msg' => '¡Empleado actualizado!',
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
        User::findOrFail($request->id)->delete();
        return response()->json([
            'msg' => '¡Empleado eliminado!',
        ]);
    }
}
