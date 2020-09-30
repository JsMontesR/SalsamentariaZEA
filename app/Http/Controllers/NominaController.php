<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Exceptions\FondosInsuficientesException;
use App\Nomina;
use App\Repositories\Cajas;
use App\Repositories\Nominas;
use Illuminate\Http\Request;

class NominaController extends Controller
{

    protected $nominas;
    protected $cajas;

    public function __construct(Nominas $nominas, Cajas $cajas)
    {
        $this->nominas = $nominas;
        $this->cajas = $cajas;
    }


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

        //Validación de la factibilidad de la transacción
        $request->validate($this->validationRules);
        $caja = Caja::findOrFail(1);
        if (!$this->cajas->isPagable($caja, $request->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }

        // Ejecución de la transacción
        $nomina = $this->nominas->store($request);
        $this->cajas->pagar($caja, $nomina, $request->parteEfectiva, $request->parteCrediticia);

        return response()->json([
            'msg' => '¡Pago de nómina realizado!',
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
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);

        // Ejecución de la transacción
        $nomina = Nomina::find($request->id);
        $movimiento = $nomina->movimientos->first();
        $this->cajas->anularPago($movimiento);
        $this->nominas->anular($nomina);

        return response()->json([
            'msg' => '¡Pago de nomina anulado!',
        ]);
    }
}
