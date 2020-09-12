<?php

namespace App\Http\Controllers;


use App\Movimiento;
use App\Repositories\Cajas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class MovimientoController extends Controller
{
    protected $cajas;

    public function __construct(Cajas $cajas)
    {
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
     * Registra una entrada.
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
