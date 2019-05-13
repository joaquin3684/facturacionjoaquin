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

            $user = factory(App\User::class)->create([ 'nombre' => 'prueba', 'password' => Hash::make('prueba')]);

            $this->call(PerfilesSeeder::class);

            $user->perfiles()->attach(1);

        });

    }
}
