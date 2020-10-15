<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Cierre;
use App\Repositories\Cajas;
use Illuminate\Http\Request;

class CierresController extends Controller
{

    public function __construct(Cajas $cajas)
    {
        $this->cajas = $cajas;
    }

    /**
     * Return list of the resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function list()
    {
        return datatables(Cierre::query())->toJson();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarCierre(Request $request)
    {
        $this->cajas->generarCierre(Caja::findOrFail(1));
        return response()->json([
            'msg' => '¡Cierre realizado con éxito!',
        ]);
    }


}
