<?php

use Illuminate\Database\Seeder;
use App\Producto;
use App\ProductoTipo;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $producto = new Producto();
        $producto->nombre = "Queso cuajada";
        $producto->tipo_id = 1;
        $producto->categoria = ProductoTipo::UNITARIO;
        $producto->costo = 2000;
        $producto->utilidad = 10;
        $producto->precio = 2200;
        $producto->stock = 50;
        $producto->save();
    }
}
