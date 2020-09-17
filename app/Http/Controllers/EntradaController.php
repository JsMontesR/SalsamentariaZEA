<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Entrada;
use App\Exceptions\FondosInsuficientesException;
use App\Movimiento;
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
        'fechapago' => 'required|date',
        'productos_entrada' => 'required',
    ];

    public $customMessages = [
        'productos_entrada.required' => 'La tabla de productos de la entrada debe contener productos',
        'fechapago.required' => 'Por favor ingrese la fecha límite del pago',
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
        return datatables(Entrada::query()->with(['empleado', 'proveedor', 'productos']))
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
     * Procesa el pago de una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function pagar(Request $request)
    {
        //Validación de la factibilidad de la transacción
        $request->validate($this->validationIdRule);
        $entrada = Entrada::findOrFail($request->id);
        if (!$this->entradas->isEntradaPagable($entrada)) {
            throw ValidationException::withMessages(["valor" => "La entrada seleccionada ya fue pagada en su totalidad"]);
        }
        if (!$this->cajas->isMontosPagoValidos($request->parteEfectiva, $request->parteCrediticia, $entrada->saldo)) {
            throw ValidationException::withMessages(["valor" => "La suma de los montos a pagar es superior al saldo pendiente"]);
        }
        $caja = Caja::findOrFail(1);
        if (!$this->cajas->isPagable($caja, $request->parteEfectiva)) {
            throw FondosInsuficientesException::withMessages(["valor" => "Operación no realizable, saldo en caja insuficiente"]);
        }
        // Ejecución de la transacción
        $this->cajas->pagar($caja, $entrada, $request->parteEfectiva, $request->parteCrediticia);
        return response()->json([
            'msg' => '¡Pago de entrada realizado!',
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
}
