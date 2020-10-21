<?php

namespace App\Repositories;

use App\Servicio;
use App\TipoServicio;
use App\User;
use Illuminate\Http\Request;

class Servicios
{

    /**
     * Registra un servicio.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $servicio = new Servicio();
        $servicio->tipoServicio()->associate(TipoServicio::findOrFail($request->servicio_id));
        $servicio->empleado()->associate(auth()->user());
        $servicio->valor = $request->valor;
        $servicio->saldo = $request->valor;
        $servicio->fechapago = $request->fechapago;
        $servicio->save();
        $servicio->refresh();
        return $servicio;
    }

    /**
     * Anula el servicio correspondiente
     * @param Servicio $nomina
     */
    public function anular(Servicio $servicio)
    {
        $servicio->delete();
    }
}

?>
