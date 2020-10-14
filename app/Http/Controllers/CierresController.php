<?php

namespace App\Http\Controllers;

use App\Caja;
use Illuminate\Http\Request;

class CierresController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $caja = Caja::findOrFail($request->caja_id);

        return response()->json([
            'msg' => '¡Cierre realizado con éxito!',
        ]);
    }
}
