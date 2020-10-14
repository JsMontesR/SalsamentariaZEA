<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("reportes");
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarVentas(Request $request)
    {
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        Log::info($fechaInicio);
        Log::info($fechaFin);
        return datatables(Venta::query()->select('ventas.*')->with(['empleado', 'cliente', 'productos']))
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporteVentas()
    {
        return view("reportes.reporteVentas");
    }
}
