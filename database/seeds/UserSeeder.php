<?php

use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "admin",
            'rol_id' => 1,
            'email' => 'admin@admin.com',
            'password' => Hash::make('1234'),
        ]);
    }
}
