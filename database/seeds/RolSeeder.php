<?php

use Illuminate\Database\Seeder;
use DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rols')->insert([
            'name' => "admin",
        ]);
        DB::table('rols')->insert([
            'name' => "empleado",
        ]);
        DB::table('rols')->insert([
            'name' => "cliente",
        ]);
    }
}
