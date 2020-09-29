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
        $user->name = 'Wilmar Zea';
        $user->rol_id = 1;
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('1234');
        $user->salario = 1000000;
        $user->save();

        $cliente = new User;
        $cliente->name = 'Sebastian Montes';
        $cliente->rol_id = 3;
        $cliente->email = 'sebas@cliente.com';
        $cliente->save();
    }
}
