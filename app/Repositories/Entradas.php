<?php

namespace App\Repositories;

use App\Entrada;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class Entradas
{

    /**
     * Registra una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entrada = new Entrada();
        $entrada->fechapago = $request->fechapago;
        $entrada->proveedor()->associate(Proveedor::findOrFail($request->proveedor_id));
        $entrada->empleado()->associate(auth()->user());
        $entrada->save();
        $costo = 0;
        foreach ($request->productos_entrada as $producto) {
            $entrada->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $producto["costo"]]);
            $productoActual = Producto::findOrFail($producto["id"]);
            $productoActual->costo = $producto["costo"] / $producto["cantidad"];
            $precio = $productoActual->costo * (1 + $productoActual->utilidad / 100);
            $productoActual->precio = $precio;
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock + $producto["cantidad"];
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                $productoActual->stock = $productoActual->stock + ($producto["cantidad"] * 1000);
            }
            $productoActual->save();
            $costo += $producto["costo"];
        }
        $entrada->valor = $costo;
        $entrada->save();
        return $entrada;
    }


    /**
     * Verifica cual producto de una entrada no es descontabel del inventario
     * @param Entrada $entrada
     * @return bool|string
     */
    public function getNoDescontable(Entrada $entrada)
    {
        foreach ($entrada->productos as $producto) {
            if ($producto->stock < $producto->pivot->cantidad) {
                return $producto->nombre . " (Con el id: " . $producto->id . ")";
            }
        }
        return null;
    }

    /**
     * Descuenta del inventario los productos de la entrada correspondiente
     * @param Entrada $entrada
     */
    public function anular(Entrada $entrada)
    {
        foreach ($entrada->productos as $producto) {
            if ($producto->categoria == ProductoTipo::UNITARIO) {
                $producto->stock = $producto->stock - $producto->pivot->cantidad;
            } elseif ($producto->categoria == ProductoTipo::GRANEL) {
                $producto->stock = $producto->stock - ($producto->pivot->cantidad * 1000);
            }
            $producto->save();
        }
    }

    public function isEntradaPagable(Entrada $entrada)
    {
        return $entrada->fechapagado == null;
    }
}

?>
