<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Entrada;
use App\Exceptions\FondosInsuficientesException;
use App\Movimiento;
use App\ProductoTipo;
use App\Repositories\Cajas;
use App\Repositories\Entradas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class EntradaController extends Controller
{

    protected $entradas;
    protected $cajas;

    public function __construct(Entradas $entradas, Cajas $cajas)
    {
        $this->entradas = $entradas;
        $this->cajas = $cajas;
    }

    public $validationRules = [
        'proveedor_id' => 'required|integer|min:1',
        'productos_entrada' => 'required',
    ];

    public $customMessages = [
        'productos_entrada.required' => 'La tabla de productos de la entrada debe contener productos',
        'proveedor_id.required' => 'Por favor seleccione un proveedor de la tabla'
    ];

    public $validationIdRule = ['id' => 'required|integer|min:1'];

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("entradas");
    }

    /**
     * Return list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function list()
    {
        return datatables(Entrada::query()->select('entradas.*')->with(['empleado', 'proveedor', 'productos']))
            ->toJson();
    }

    /**
     * Return list of pagos associated to the resource in storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function pagos($id)
    {
        return datatables(Movimiento::query()->with('empleado')->whereHasMorph('movimientoable', ['App\Entrada'], function (Builder $query) use ($id) {
            $query->where('movimientoable_id', '=', $id);
            $query->where('tipo', '=', Movimiento::EGRESO);
        }))->toJson();
    }

    /**
     * Registra una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationRules, $this->customMessages);
        foreach ($request->productos_entrada as $producto) {
            if (empty($producto["cantidad"]) || empty($producto["costo"])) {
                throw ValidationException::withMessages(['productos_entrada' => 'La tabla de productos de la entrada debe contener productos con sus respectivas cantidades y costos']);
            }
        }
        // Ejecución de la transacción
        $this->entradas->store($request);
        return response()->json([
            'msg' => '¡Entrada registrada!',
        ]);
    }

    /**
     * Actualiza una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate(['fechapago' => 'required|date']);
        $entrada = Entrada::findOrFail($request->id);

        // Ejecución de la transacción
        $entrada->fechapago = $request->fechapago;
        $entrada->save();

        return response()->json([
            'msg' => '¡Datos de la entrada actualizados!',
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
        $entrada = Entrada::find($request->id);
        if (($problem = $this->entradas->getNoDescontable($entrada)) != null) {
            throw ValidationException::withMessages(["id" => "No se cuenta con las existencias suficientes de " . $problem . " para anular la entrada"]);
        }
        // Ejecución de la transacción
        $this->cajas->anularTodosLosPagos($entrada);
        $this->entradas->anular($entrada);
        return response()->json([
            'msg' => '¡Entrada anulada!',
        ]);
    }

    /**
     * Print the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function imprimirComprobante(Request $request)
    {
        $entrada = Entrada::findOrFail($request->id);
        return $this->imprimirLogic($entrada);
    }

    public function imprimirLogic(Entrada $entrada)
    {
        $fecha = $entrada->created_at;
        $fechaActual = now();
        $fechaLimitePago = $entrada->fechapago;
        $fechaDePago = $entrada->fechapagado;
        $descripcion = "Comprobante de entrada # " . $entrada->id;
        $lugarEntrega = $entrada->lugarentrega;
        // Datos del cliente
        $tituloParticipante = "Proveedor";
        $nombreParticipante = $entrada->proveedor->nombre;
        $direccionParticipante = $entrada->proveedor->direccion;
        $telefonoParticipante = $entrada->proveedor->celular;
        $emailParticipante = $entrada->proveedor->email;
        $tituloEmpleado = $entrada->empleado->name;
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
        foreach ($entrada->productos as $producto) {
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
        $total = "$ " . number_format($entrada->valor, 0);
        $saldo = "$ " . number_format($entrada->saldo, 0);
        $dineroAbonado = "$ " . number_format($entrada->abonado, 0);
        $pdf = \PDF::loadView("print.comprobanteEntrada", compact('descripcion', 'fecha', 'fechaActual', 'tituloParticipante',
            'nombreParticipante', 'nombreEmpresa', 'direccionParticipante', 'telefonoParticipante', 'tituloEmpleado', 'emailParticipante',
            'direccionEmpresa', 'telefonoEmpresa', 'emailEmpresa', 'total', 'registros', 'fechaLimitePago', 'fechaDePago', 'razonSocial',
            'NIT', 'personaNatural', 'saldo', 'dineroAbonado', 'lugarEntrega'));
        return $pdf->stream('comprobante.pdf');
    }

}
