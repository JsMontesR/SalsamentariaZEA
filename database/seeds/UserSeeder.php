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

        $user = new User;
        $user->name = 'Sebastian Montes';
        $user->rol_id = 3;
        $user->email = 'sebas@cliente.com';
        $user->save();
    }
}
