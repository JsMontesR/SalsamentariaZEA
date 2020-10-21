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
        return datatables()->eloquent(Servicio::query()->select('servicios.*')->with('tipoServicio', 'empleado'))->toJson();
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
     * Actualiza un servicio.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $servicio = Servicio::findOrFail($request->id);

        // Ejecución de la transacción
        $servicio->fechapago = $request->fechapago;
        $servicio->save();

        return response()->json([
            'msg' => '¡Datos del servicio actualizados!',
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

    /**
     * Print the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function imprimirCombrobante(Request $request)
    {
        $servicio = Servicio::findOrFail($request->id);
        return $this->imprimirLogic($servicio);
    }

    public function imprimirLogic(Servicio $servicio)
    {
        $fecha = $servicio->created_at;
        $fechaActual = now();
        $fechaLimitePago = $servicio->fechapago;
        $fechaDePago = $servicio->fechapagado;
        $descripcion = "Servicio # " . $servicio->id;
        // Datos del cliente
        $tituloParticipante = "Cliente";
        $numeroServicio = $servicio->tipoServicio->id;
        $nombreServicio = $servicio->tipoServicio->nombre;
        $tituloEmpleado = $servicio->empleado->name;
        // Datos de la empresa
        $nombreEmpresa = "Salsamentaría ZEA";
        $direccionEmpresa = "Calle 21 #24-43 B/San José";
        $telefonoEmpresa = "CEL 3112300293";
        $emailEmpresa = "salsamentariazea@mail.com";
        $razonSocial = "SALSAMENTARÍA ZEA";
        $NIT = "NIT 1856151593-8";
        $personaNatural = "JOSE WILMAR GUEVARA ZEA";
        $registros = array();
        $valorBase = "$ " . number_format($servicio->tipoServicio->costo, 0);
        $total = "$ " . number_format($servicio->valor, 0);
        $saldo = "$ " . number_format($servicio->saldo, 0);
        $dineroAbonado = "$ " . number_format($servicio->valor - $servicio->saldo, 0);
        $pdf = \PDF::loadView("print.comprobante", compact('descripcion', 'fecha', 'fechaActual',
            'tituloParticipante', 'nombreServicio', 'numeroServicio', 'nombreEmpresa',
            'tituloEmpleado', 'direccionEmpresa', 'telefonoEmpresa', 'emailEmpresa',
            'total', 'registros', 'fechaLimitePago', 'fechaDePago', 'razonSocial', 'NIT', 'personaNatural',
            'saldo', 'dineroAbonado', 'valorBase'));
        return $pdf->stream('comprobante.pdf');

    }
}
