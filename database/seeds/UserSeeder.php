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
        $user->id = 0;
        $user->name = 'admin';
        $user->rol_id = 1;
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('1234');
        $user->save();
    }
}
