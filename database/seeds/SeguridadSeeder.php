<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeguridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Db::transaction(function(){

            $user = factory(App\User::class)->create([ 'nombre' => 'PRUEBA', 'password' => Hash::make('PRUEBA')]);

            $this->call(ObrasSocialesSeeder::class);
            $this->call(PerfilesSeeder::class);

            $user->perfiles()->attach(1);

        });

    }
}
