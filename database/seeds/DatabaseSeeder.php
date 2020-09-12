<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CajaSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(ProductoTiposSeeder::class);
        $this->call(ProductosSeeder::class);
    }
}
