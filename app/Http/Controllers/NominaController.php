<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Exceptions\FondosInsuficientesException;
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
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("nominas");
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
        $nomina->valor = $request->parteCrediticia + $request->parteEfectiva;
        try {
            Caja::findOrFail(1)->pagar($nomina, $request->parteEfectiva, $request->parteCrediticia);
        } catch (FondosInsuficientesException $e) {
            throw ValidationException::withMessages(["valor" => $e->getMessage()]);
        }
        return response()->json([
            'msg' => '¡Nomina pagada!',
        ]);
    }

    /**
     * Retrive the specified resources in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function list()
    {
        return datatables()->eloquent(Nomina::query()->with('empleado'))->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function undoPay(Request $request)
    {
        $request->validate($this->validationRules);
        $nomina = Nomina::find($request->id);
        $movimiento = $nomina->movimientos->first();
        $nomina->delete();
        Caja::findOrFail(1)->cobrar($nomina, $movimiento->parteEfectiva, $movimiento->parteCrediticia);
        return response()->json([
            'msg' => '¡Nomina anulada!',
        ]);
    }
}
