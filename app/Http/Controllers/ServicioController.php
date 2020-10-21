<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Exceptions\FondosInsuficientesException;
use App\Movimiento;
use App\Nomina;
use App\Repositories\Cajas;
use App\Repositories\Nominas;
use App\Repositories\Servicios;
use App\Servicio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServicioController extends Controller
{

    protected $servicios;
    protected $cajas;

    public function __construct(Servicios $servicios, Cajas $cajas)
    {
        $this->servicios = $servicios;
        $this->cajas = $cajas;
    }


    public $validationRules = [
        'servicio_id' => 'required|integer|min:1',
        'valor' => 'required|integer|min:0',
    ];

    public $validationIdRule = ['id' => 'required|integer|min:0'];

    public $customMessages = [
        'servicio_id.required' => 'Por favor seleccione un tipo de servicio de la tabla',
        'valor.required' => 'Por favor especifique el total a pagar'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("servicios");
    }

    /**
     * Retrive the specified resources in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function list()
    {
        return datatables()->eloquent(Servicio::query()->with('tipoServicio'))->toJson();
    }

    /**
     * Return list of pagos associated to the resource in storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function pagos($id)
    {
        return datatables(Movimiento::query()->with('empleado')->whereHasMorph('movimientoable', ['App\Servicio'], function (Builder $query) use ($id) {
            $query->where('movimientoable_id', '=', $id);
            $query->where('tipo', '=', Movimiento::EGRESO);
        }))->toJson();
    }

    /**
     * Registra una servicio.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules, $this->customMessages);
        $servicio = $this->servicios->store($request);
        return response()->json([
            'msg' => '¡Servicio registrado!',
            'data' => $servicio
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
        $servicio = Servicio::find($request->id);
        // Ejecución de la transacción
        $this->cajas->anularTodosLosPagos($servicio);
        $this->servicios->anular($servicio);
        return response()->json([
            'msg' => '¡Servicio anulado!',
        ]);
    }
}
