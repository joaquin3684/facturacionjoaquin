<?php

use Illuminate\Database\Seeder;

class InicioSistema extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function(){
            $this->call(SeguridadSeeder::class);
            $this->call(ProductoSeeder::class);
            $this->call(PublicacionSeeder::class);
        });
    }
}
