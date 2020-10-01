<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Exceptions\FondosInsuficientesException;
use App\Movimiento;
use App\Nomina;
use App\Repositories\Cajas;
use App\Repositories\Nominas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
        'fechapago' => 'required|date',
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
        return view("nominas");
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
     * Return list of pagos associated to the resource in storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function pagos($id)
    {
        return datatables(Movimiento::query()->with('empleado')->whereHasMorph('movimientoable', ['App\Nomina'], function (Builder $query) use ($id) {
            $query->where('movimientoable_id', '=', $id);
            $query->where('tipo', '=', Movimiento::EGRESO);
        }))->toJson();
    }

    /**
     * Registra una nómina.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $this->nominas->store($request);
        return response()->json([
            'msg' => '¡Nomina registrada!',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function pagar(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $nomina = Nomina::findOrFail($request->id);
        if (!$this->nominas->isNominaPagable($nomina)) {
            throw ValidationException::withMessages(["valor" => "La entrada seleccionada ya fue pagada en su totalidad"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $nomina->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        $caja = Caja::findOrFail(1);
        if (!$this->cajas->isPagable($caja, $request->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        // Ejecución de la transacción
        $this->cajas->pagar($caja, $nomina, $request->parteEfectiva, $request->parteCrediticia);
        return response()->json([
            'msg' => '¡Pago de nomina realizado!',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function anular(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $nomina = Nomina::find($request->id);
        // Ejecución de la transacción
        $this->cajas->anularTodosLosPagos($nomina);
        $this->nominas->anular($nomina);
        return response()->json([
            'msg' => '¡Nomina anulada!',
        ]);
    }
}
