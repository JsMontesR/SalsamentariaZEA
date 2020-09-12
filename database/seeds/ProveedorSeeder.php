<?php

use Illuminate\Database\Seeder;
use App\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proveedor = new Proveedor();
        $proveedor->nombre = "Colanta";
        $proveedor->save();
    }
}
