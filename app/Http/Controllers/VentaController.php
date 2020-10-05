<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Exceptions\FondosInsuficientesException;
use App\Movimiento;
use App\Repositories\Cajas;
use App\Repositories\Ventas;
use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class VentaController extends Controller
{

    protected $ventas;
    protected $cajas;

    public function __construct(Ventas $ventas, Cajas $cajas)
    {
        $this->ventas = $ventas;
        $this->cajas = $cajas;
    }

    public $validationRules = [
        'cliente_id' => 'required|integer|min:1',
        'fechapago' => 'required|date',
        'productos_venta' => 'required',
    ];

    public $validationRegisterCharge = [
        'cliente_id' => 'required|integer|min:1',
        'productos_venta' => 'required',
    ];

    public $customMessages = [
        'productos_venta.required' => 'La tabla de productos de la venta debe contener productos',
        'fechapago.required' => 'Por favor ingrese la fecha límite del pago',
        'cliente_id.required' => 'Por favor seleccione un cliente de la tabla'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("ventas");
    }

    /**
     * Return list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function list()
    {
        return datatables(Venta::query()->with(['empleado', 'cliente', 'productos']))
            ->toJson();
    }

    /**
     * Return list of pagos associated to the resource in storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function cobros($id)
    {
        return datatables(Movimiento::query()->with('empleado')->whereHasMorph('movimientoable', ['App\Venta'], function (Builder $query) use ($id) {
            $query->where('movimientoable_id', '=', $id);
            $query->where('tipo', '=', Movimiento::INGRESO);
        }))->toJson();
    }

    /**
     * Registra una venta.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $this->validarVenta($request);
        // Ejecución de la transacción
        $this->ventas->store($request);
        return response()->json([
            'msg' => '¡Venta registrada!',
        ]);
    }

    /**
     * Registra una venta y la cobra en efectivo y en su totalidad.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeCharge(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $this->validarVenta($request, $this->validationRegisterCharge);
        // Ejecución de la transacción
        $venta = $this->ventas->store($request);
        $caja = Caja::findOrFail(1);
        $this->cajas->cobrar($caja, $venta, $venta->saldo);
        return response()->json([
            'msg' => '¡Venta registrada y cobrada en efectivo!',
        ]);
    }

    public function validarVenta(Request $request, $validationRules = null)
    {
        if ($validationRules == null) {
            $validationRules = $this->validationRules;
        }
        $request->validate($validationRules, $this->customMessages);
        foreach ($request->productos_venta as $producto) {
            if (empty($producto["cantidad"]) || empty($producto["costo"])) {
                throw ValidationException::withMessages(['productos_venta' => 'La tabla de productos de la venta debe contener productos con sus respectivas cantidades y costos']);
            }
        }
        if (($problem = $this->ventas->getNoDescontable($request)) != null) {
            throw ValidationException::withMessages(["productos_venta" => "No se cuenta con las existencias suficientes de " . $problem . " para realizar la venta"]);
        }
    }

    /**
     * Actualiza una venta.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate(['fechapago' => 'required|date']);
        $venta = Venta::findOrFail($request->id);

        // Ejecución de la transacción
        $venta->fechapago = $request->fechapago;
        $venta->save();

        return response()->json([
            'msg' => '¡Datos de la venta actualizados!',
        ]);
    }

    /**
     * Procesa el cobro de una venta.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function cobrar(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $venta = Venta::findOrFail($request->id);
        if (!$this->ventas->isVentaCobrable($venta)) {
            throw ValidationException::withMessages(["valor" => "La venta seleccionada ya fue pagada en su totalidad"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $venta->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        $caja = Caja::findOrFail(1);
        // Ejecución de la transacción
        $this->cajas->cobrar($caja, $venta, $request->parteEfectiva, $request->parteCrediticia);
        return response()->json([
            'msg' => '¡Pago de venta realizado!',
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
        $venta = Venta::find($request->id);
        if (($problem = $this->cajas->getCobroNoAnulable($venta)) != null) {
            throw FondosInsuficientesException::withMessages(["id" => "No se puede anular " . $problem . " el saldo en caja es insuficiente"]);
        }

        // Ejecución de la transacción
        $this->cajas->anularTodosLosCobros($venta);
        $this->ventas->anular($venta);
        return response()->json([
            'msg' => '¡Venta anulada!',
        ]);
    }
}
