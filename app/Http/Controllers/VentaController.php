<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Exceptions\FondosInsuficientesException;
use App\Movimiento;
use App\ProductoTipo;
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
        return datatables(Venta::query()->select('ventas.*')->with(['empleado', 'cliente', 'productos']))
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
        $venta = $this->ventas->store($request);
        $venta->productos;
        // Ejecución de la transacción
        return response()->json([
            'msg' => '¡Venta registrada!',
            'data' => $venta
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
        $this->validarVenta($request, $this->validationRules);
        // Ejecución de la transacción
        $venta = $this->ventas->store($request);
        $caja = Caja::findOrFail(1);
        $this->cajas->cobrar($caja, $venta, $venta->saldo);
        return response()->json([
            'msg' => '¡Venta registrada y cobrada en efectivo!',
        ]);
    }

    /**
     *
     * Valida una venta
     * @param Request $request
     * @param null $validationRules
     * @throws ValidationException
     */
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
        $request->validate($this->validationIdRule);
        $venta = Venta::findOrFail($request->id);

        // Ejecución de la transacción
        $venta->fechapago = $request->fechapago;
        $venta->save();

        return response()->json([
            'msg' => '¡Datos de la venta actualizados!',
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

    /**
     * Print the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function imprimirFactura(Request $request)
    {
        $venta = Venta::findOrFail($request->id);
        return $this->imprimirLogic($venta);
    }

    public function imprimirLogic(Venta $venta)
    {
        $fecha = $venta->created_at;
        $fechaActual = now();
        $fechaLimitePago = $venta->fechapago;
        $fechaDePago = $venta->fechapagado;
        $descripcion = "Venta # " . $venta->id;
        // Datos del cliente
        $tituloParticipante = "Cliente";
        $nombreParticipante = $venta->cliente->name;
        $direccionParticipante = $venta->cliente->direccion;
        $telefonoParticipante = $venta->cliente->celular;
        $tituloEmpleado = $venta->cliente->fijo;
        $emailParticipante = $venta->cliente->email;
        $tituloEmpleado = $venta->empleado->name;
        // Datos de la empresa
        $nombreEmpresa = "Salsamentaría ZEA";
        $direccionEmpresa = "Calle 21 #24-43 B/San José";
        $telefonoEmpresa = "CEL 3112300293";
        $emailEmpresa = "salsamentariazea@mail.com";
        $razonSocial = "SALSAMENTARÍA ZEA";
        $NIT = "NIT 1856151593-8";
        $personaNatural = "JOSE WILMAR GUEVARA ZEA";
        $registros = array();
        $count = 1;
        foreach ($venta->productos as $producto) {
            $registro = new \stdClass();
            $registro->numero = $count++;
            $registro->nombre = $producto->nombre;
            if ($producto->pivot->unidad == ProductoTipo::GRAMOS) {
                $registro->valorUnitario = "$ " . number_format(1000 * ($producto->pivot->costo / $producto->pivot->cantidad), 0);
                $registro->cantidad = $producto->pivot->cantidad . " g";
            } else if ($producto->pivot->unidad == ProductoTipo::KILOGRAMOS) {
                $registro->valorUnitario = "$ " . number_format($producto->pivot->costo / $producto->pivot->cantidad, 0);
                $registro->cantidad = $producto->pivot->cantidad . " kg";
            } else {
                $registro->valorUnitario = "$ " . number_format($producto->pivot->costo / $producto->pivot->cantidad, 0);
                $registro->cantidad = $producto->pivot->cantidad . " un";
            }
            $registro->total = "$ " . number_format($producto->pivot->costo, 0);
            array_push($registros, $registro);
        }
        $total = "$ " . number_format($venta->valor, 0);
        $saldo = "$ " . number_format($venta->saldo, 0);
        $dineroAbonado = "$ " . number_format($venta->valor - $venta->saldo, 0);
        $concepto = "Factura de venta";
        $descripcion = $concepto . " #" . $venta->id;
        $pdf = \PDF::loadView("print.factura", compact('descripcion', 'fecha', 'fechaActual', 'tituloParticipante',
            'nombreParticipante', 'nombreEmpresa', 'direccionParticipante', 'telefonoParticipante', 'tituloEmpleado', 'emailParticipante',
            'direccionEmpresa', 'telefonoEmpresa', 'emailEmpresa', 'total', 'registros', 'fechaLimitePago', 'fechaDePago', 'razonSocial',
            'NIT', 'personaNatural', 'saldo', 'dineroAbonado'));
        return $pdf->stream('factura.pdf');

    }


}
