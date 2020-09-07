<?php

namespace App\Http\Controllers;

use App\Movimiento;

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
     * @return \Illuminate\Http\Response
     */
    public function list(){
        return datatables()->eloquent(Movimiento::query())->toJson();
    }

}
