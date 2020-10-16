<?php

use Illuminate\Database\Seeder;
use App\TipoServicio;

class TipoServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoServicio = new TipoServicio();
        $tipoServicio->nombre = "Arrendamiento";
        $tipoServicio->costo = 800000;
        $tipoServicio->save();
        $tipoServicio = new TipoServicio();
        $tipoServicio->nombre = "Agua";
        $tipoServicio->costo = 90000;
        $tipoServicio->save();
        $tipoServicio = new TipoServicio();
        $tipoServicio->nombre = "Electricidad";
        $tipoServicio->costo = 140000;
        $tipoServicio->save();
    }
}
