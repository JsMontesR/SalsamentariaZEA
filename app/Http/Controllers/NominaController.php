<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Nomina;
use App\User;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Validation\ValidationException;

class NominaController extends Controller
{

    public $validationRules = [
        'empleado_id' => 'required|integer|min:1',
        'valor' => 'required|integer|min:0',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nominas = DB::table('nominas')->select(
            DB::raw('nominas.id as Id'),
            DB::raw('users.id as "Id empleado"'),
            DB::raw('users.name as "Nombre"'),
            DB::raw('users.di as "Documento de identidad"'),
            DB::raw('users.salario as "Salario"'),
            DB::raw('nominas.valor as "Valor pagado"'),
            DB::raw('nominas.created_at as "Fecha de creación"'),
            DB::raw('nominas.updated_at as "Fecha de actualización"')
        )
            ->join("users", "nominas.empleado_id", "=", "users.id")->get();

        $empleados = DB::table('users')->select(
            DB::raw('id as Id'),
            DB::raw('name as "Nombre"'),
            DB::raw('di as "Documento de identidad"'),
            DB::raw('salario as "Salario"')
        )->get();

        return view("nominas", compact("nominas", "empleados"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        $request->validate($this->validationRules);
        $nomina = new Nomina();
        $nomina->empleado()->associate(User::findOrFail($request->empleado_id));
        $nomina->valor = $request->valor;
        $caja = Caja::findOrFail(1);
        try {
            $caja->sacarDinero($nomina->valor);
        } catch (Exception $e) {
            throw ValidationException::withMessages(["valor" => $e->getMessage()]);
        }
        $nomina->save();
        return back()->with('success', 'Nómina pagada');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Nomina $nomina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nomina $nomina)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Nomina $nomina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nomina $nomina)
    {
        //
    }
}
