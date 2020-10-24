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
        'valor' => 'required|integer|min:0',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    public $customMessages = [
        'empleado_id.required' => 'Por favor seleccione un empleado de la tabla',
        'valor.required' => 'Por favor especifique el total a pagar'
    ];

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
        return datatables()->eloquent(Nomina::query()->select('nominas.*')->with('empleado'))->toJson();
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
        $request->validate($this->validationRules, $this->customMessages);
        $nomina = $this->nominas->store($request);
        return response()->json([
            'msg' => '¡Nomina registrada!',
            'data' => $nomina
        ]);
    }

    /**
     * Actualiza una nómina.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $nomina = Nomina::findOrFail($request->id);

        // Ejecución de la transacción
        $nomina->fechapago = $request->fechapago;
        $nomina->save();

        return response()->json([
            'msg' => '¡Datos de la nómina actualizados!',
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
