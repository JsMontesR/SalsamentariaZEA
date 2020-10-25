<?php

namespace App\Repositories;

use App\Entrada;
use App\Ingreso;
use App\Producto;
use App\ProductoTipo;
use Illuminate\Http\Request;

class Ingresos
{

    /**
     * Registra un ingreso.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ingreso = new Ingreso();
        $ingreso->empleado()->associate(auth()->user());
        $ingreso->save();
        $costo = 0;
        foreach ($request->productos_ingreso as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock + $producto["cantidad"];
                $subCosto = $producto["cantidad"] * $productoActual->costo;
                $ingreso->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $subCosto]);
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                if ($producto["unidad"] == ProductoTipo::GRAMOS) {
                    $productoActual->stock = $productoActual->stock + $producto["cantidad"];
                    $subCosto = $producto["cantidad"] * ($productoActual->costo / 1000);
                } else if ($producto["unidad"] == ProductoTipo::KILOGRAMOS) {
                    $productoActual->stock = $productoActual->stock + ($producto["cantidad"] * 1000);
                    $subCosto = $producto["cantidad"] * $productoActual->costo;
                }
                $ingreso->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'unidad' => $producto["unidad"], 'costo' => $subCosto]);
            }
            $productoActual->save();
            $costo += $subCosto;
        }
        $ingreso->costo = $costo;
        $ingreso->valor = $request->valor;
        $ingreso->save();
        return $ingreso;
    }


    /**
     * Verifica cual producto de un ingreso no es descontable del inventario
     * @param Entrada $ingreso
     * @return bool|string
     */
    public function getNoDescontable(Ingreso $ingreso)
    {
        foreach ($ingreso->productos as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            $cantidad = $producto["cantidad"];
            if ($productoActual->categoria == ProductoTipo::GRANEL) {
                if ($producto["unidad"] == ProductoTipo::GRAMOS) {
                    $cantidad = $cantidad;
                } else if ($producto["unidad"] == ProductoTipo::KILOGRAMOS) {
                    $cantidad = $cantidad * 1000;
                }
            }
            if ($cantidad > $productoActual->stock) {
                return $productoActual->nombre . " (Con el id: " . $productoActual->id . ") faltan " . ($cantidad - $productoActual->stock) . " " . ($productoActual->categoria == ProductoTipo::GRANEL ? "gramos " : "unidades ");
            }
        }
        return null;
    }


    /**
     * Descuenta del inventario los productos del ingreso correspondiente
     * @param Entrada $ingreso
     * @throws \Exception
     */
    public function anular(Ingreso $ingreso)
    {
        foreach ($ingreso->productos as $producto) {
            if ($producto->categoria == ProductoTipo::UNITARIO) {
                $producto->stock = $producto->stock - $producto->pivot->cantidad;
            } elseif ($producto->categoria == ProductoTipo::GRANEL) {
                $producto->stock = $producto->stock - ($producto->pivot->cantidad * 1000);
            }
            $producto->save();
        }
        $ingreso->delete();
    }

}

?>
