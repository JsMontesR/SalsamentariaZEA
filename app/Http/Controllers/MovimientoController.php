<?php

namespace App\Http\Controllers;

use App\Exceptions\FondosInsuficientesException;
use App\Caja;
use App\Movimiento;
use App\Nomina;
use App\ProductoTipo;
use App\Venta;
use App\Entrada;
use App\Repositories\Cajas;
use App\Repositories\Entradas;
use App\Repositories\Nominas;
use App\Repositories\Ventas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class MovimientoController extends Controller
{
    protected $cajas, $ventas, $nominas, $entradas;

    public $validationRules = [
        'parteEfectiva' => 'required_without:parteCrediticia',
        'parteCrediticia' => 'required_without:parteEfectiva'
    ];

    public function __construct(Cajas $cajas, Ventas $ventas, Nominas $nominas, Entradas $entradas)
    {
        $this->ventas = $ventas;
        $this->nominas = $nominas;
        $this->entradas = $entradas;
        $this->cajas = $cajas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('movimientos');
    }

    /**
     * Genera un pago.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generarPago(Request $request)
    {
        $request->validate($this->validationRules);
        $tipo = $request->movimientoable;
        $caja = Caja::findOrFail(1);
        switch ($tipo) {
            case "entrada":
                $movimientoable = Entrada::findOrFail($request->id);
                $repository = $this->entradas;
            case "nomina":
                $movimientoable = Nomina::findOrFail($request->id);
                $repository = $this->nominas;
            default:
                throw ValidationException::withMessages(["movimientoable" => "El tipo de operación a pagar es indefinido"]);
        }

        if (!$repository->isProcesable($movimientoable)) {
            throw ValidationException::withMessages(["valor" => "La " . $tipo . " seleccionada ya fue pagada en su totalidad"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $movimientoable->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        if (!$this->cajas->isPagable($caja, $request->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        $this->cajas->pagar($caja, $movimientoable, $request->parteEfectiva, $request->parteCrediticia);
        return response()->json([
            'msg' => '¡Pago de ' . $tipo . ' realizado!',
        ]);
    }

    /**
     * Anula un pago.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function anularPago(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $movimiento = Movimiento::findOrFail($request->id);

        // Ejecución de la transacción
        $this->cajas->anularPago($movimiento);
        return response()->json([
            'msg' => '¡Pago anulado!',
        ]);
    }

    /**
     * Genera un cobro.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generarCobro(Request $request)
    {
        $request->validate($this->validationRules);
        $tipo = $request->movimientoable;
        $caja = Caja::findOrFail(1);
        switch ($tipo) {
            case "venta":
                $movimientoable = Venta::findOrFail($request->id);
                $repository = $this->ventas;
                break;
            default:
                throw ValidationException::withMessages(["movimientoable" => "El tipo de operación a cobrar es indefinido"]);
        }
        if (!$this->cajas->isProcesable($movimientoable)) {
            throw ValidationException::withMessages(["valor" => "La " . $tipo . " seleccionada ya fue pagada en su totalidad"]);
        }
        if ($this->cajas->generarCambio($request->parteEfectiva, $request->efectivoRecibido) < 0) {
            throw ValidationException::withMessages(["valor" => "El efectivo recibido es menor al valor efectivo a cobrar"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $movimientoable->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        return response()->json([
            'msg' => '¡Pago de ' . $tipo . ' realizado!',
            'data' => $this->cajas->cobrar($caja, $movimientoable, $request->efectivoRecibido, $request->parteEfectiva, $request->parteCrediticia)
        ]);
    }

    /**
     * Anula un cobro.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function anularCobro(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $movimiento = Movimiento::findOrFail($request->id);
        if (!$this->cajas->isPagable($movimiento->caja, $movimiento->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["id" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        // Ejecución de la transacción
        $this->cajas->anularCobro($movimiento);
        return response()->json([
            'msg' => '¡Cobro anulado!',
        ]);
    }

    /**
     * Print the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request)
    {
        $movimiento = Movimiento::findOrFail($request->idcobro);
        $venta = $movimiento->movimientoable;
        return $this->imprimirLogic($venta, $movimiento);
    }

    public function imprimirLogic(Venta $venta, Movimiento $movimiento)
    {
        // Datos del pago
        $fecha = $movimiento->created_at;
        $fechaActual = now();
        $concepto = "Recibo de pago # " . $movimiento->id;
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
        // Datos de la venta
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
        $parteEfectiva = "$ " . number_format($movimiento->parteEfectiva, 0);
        $parteCrediticia = "$ " . number_format($movimiento->parteCrediticia, 0);
        $efectivoRecibido = "$ " . number_format($movimiento->efectivoRecibido, 0);;
        $cambio = "$ " . number_format($movimiento->cambio, 0);;
        $pdf = \PDF::loadView("print.pos", compact('concepto', 'descripcion', 'fecha', 'fechaActual', 'tituloParticipante',
            'nombreParticipante', 'nombreEmpresa', 'direccionParticipante', 'telefonoParticipante', 'tituloEmpleado', 'emailParticipante',
            'direccionEmpresa', 'telefonoEmpresa', 'emailEmpresa', 'total', 'registros', 'razonSocial', 'NIT', 'personaNatural', 'efectivoRecibido',
            'cambio', 'parteEfectiva', 'parteCrediticia', 'saldo'));
        return $pdf->stream("pos.pdf");
    }

    /**
     * Retrive a list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return datatables()->eloquent(Movimiento::query()->with('empleado')->withTrashed()
        )->addColumn('valor', function (Movimiento $movimiento) {
            return $movimiento->movimientoable->valor;
        })->filterColumn('valor', function ($query, $keyword) {
            $movimientos = Movimiento::whereHasMorph('movimientoable', "*", function ($subquery) use ($keyword) {
                $subquery->where('valor', 'like', '%' . $keyword . '%')->withTrashed();
            })->get()->pluck('id')->toArray();
            $query->whereIn('id', $movimientos);
        })->toJson();
    }

}
