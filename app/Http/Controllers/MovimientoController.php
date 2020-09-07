<?php

namespace App\Http\Controllers;

use App\Movimiento;
use Illuminate\Database\Eloquent\Builder;

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
            ->whereHasMorph('movimientoable', '*', function ($query) {
                $query->withTrashed();
            })
            ->with(['movimientoable' => function ($query) {
                $query->withTrashed();
                $query->with('empleado');
            }]))->toJson();
    }

}
