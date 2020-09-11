<?php

namespace App\Repositories;

use App\Entrada;
use App\Nomina;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use App\User;
use Illuminate\Http\Request;

class Nominas
{

    /**
     * Registra una nomina.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nomina = new Nomina();
        $nomina->empleado()->associate(User::findOrFail($request->empleado_id));
        $nomina->valor = $request->parteCrediticia + $request->parteEfectiva;
        $nomina->save();
        $nomina->refresh();
        return $nomina;
    }

    /**
     * Descuenta del inventario los productos de la entrada correspondiente
     * @param Nomina $nomina
     */
    public function anular(Nomina $nomina)
    {
        $nomina->delete();
    }

    public function isEntradaPagable(Entrada $entrada)
    {
        return $entrada->fechapagado == null;
    }
}

?>
