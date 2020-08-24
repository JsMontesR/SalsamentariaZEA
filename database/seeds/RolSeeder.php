<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create(['nombre' => 'admin']);
        Rol::create(['nombre' => 'empleado']);
        Rol::create(['nombre' => 'cliente']);
    }
}
