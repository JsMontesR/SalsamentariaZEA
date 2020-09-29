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
        Rol::create(['id' => 1, 'nombre' => 'Administrador']);
        Rol::create(['id' => 2,'nombre' => 'Empleado']);
        Rol::create(['id' => 3,'nombre' => 'Cliente']);
    }
}
