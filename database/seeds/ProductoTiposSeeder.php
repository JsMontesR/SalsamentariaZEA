<?php

use Illuminate\Database\Seeder;
use App\ProductoTipo;

class ProductoTiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo = new ProductoTipo();
        $tipo->nombre = "Quesos";
        $tipo->save();
    }
}
