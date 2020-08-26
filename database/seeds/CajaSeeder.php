<?php

use Illuminate\Database\Seeder;
use App\Caja;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caja = new Caja();
        $caja->saldo = 1000000;
        $caja->save();
    }
}
