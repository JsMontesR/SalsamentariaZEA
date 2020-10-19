<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'JOSE WILMAR GUEVARA ZEA';
        $user->rol_id = 1;
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('1234');
        $user->salario = 1000000;
        $user->save();

        $cliente = new User;
        $cliente->name = 'JHON SEBASTIAN MONTES ROJAS';
        $cliente->rol_id = 3;
        $cliente->di = "1113313867";
        $cliente->celular = "3508309863";
        $cliente->fijo = "2198680";
        $cliente->direccion = "CARRERA 52 # 54 -53";
        $cliente->email = 'sebas@cliente.com';
        $cliente->save();
    }
}
