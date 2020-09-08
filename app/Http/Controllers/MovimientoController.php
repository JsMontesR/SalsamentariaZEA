<?php

namespace App\Http\Controllers;

use App\Entrada;
use App\Movimiento;
use App\Nomina;
use App\Retiro;
use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MovimientoController extends Controller
{
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
     * Retrive a list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return datatables()->eloquent(Movimiento::query()
        )->addColumn('valor', function ($movimiento) {
            return $movimiento->movimientoable->valor;
        })->addColumn('empleado', function ($movimiento) {
            return $movimiento->movimientoable->empleado->name;
        })->filterColumn('valor', function ($query, $keyword) {
            $movimientos = Movimiento::whereHasMorph('movimientoable', "*", function ($subquery) use ($keyword) {
                $subquery->where('valor', 'like', '%' . $keyword . '%');
            })->get()->pluck('id')->toArray();
            $query->whereIn('id', $movimientos);
        })->filterColumn('empleado', function ($query, $keyword) {
            $movimientos = Movimiento::whereHasMorph('movimientoable', "*", function (Builder $subquery) use ($keyword) {
                $subquery
                    ->join('users','users.id','=','empleado_id')
                    ->where('users.name', 'like', '%' . $keyword . '%');
            })->get()->pluck('id')->toArray();
            $query->whereIn('id', $movimientos);
        })->toJson();
    }

}
