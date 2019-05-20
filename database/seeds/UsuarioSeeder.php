<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function () {

            $admin = factory(App\User::class)->create(['nombre' => 'PRUEBA', 'password' => Hash::make('PRUEBA')]);
            $vendedora = factory(App\User::class)->create(['nombre' => 'VENDEDORA', 'user' => 'VENDEDORA', 'password' => Hash::make('VENDEDORA')]);
            $teamLeader = factory(App\User::class)->create(['nombre' => 'TEAM LEADER', 'user' => 'TEAMLEADER', 'password' => Hash::make('TEAMLEADER')]);
            $operadorVenta = factory(App\User::class)->create(['nombre' => 'OPERADOR', 'user' => 'OPERADOR', 'id_jefe' => $teamLeader->id, 'password' => Hash::make('OPERADOR')]);

            $this->call(PerfilesSeeder::class);


            $admin->perfiles()->attach(1);
            $admin->obrasSociales()->attach([1, 2, 3, 4, 5, 6, 7]);
            $vendedora->perfiles()->attach(14);
            $vendedora->obrasSociales()->attach([1, 2, 3, 4, 5, 6, 7]);
            $operadorVenta->perfiles()->attach(9);
            $operadorVenta->obrasSociales()->attach([1, 2, 3, 4, 5, 6, 7]);
            $teamLeader->perfiles()->attach(15);
            $teamLeader->obrasSociales()->attach([1, 2, 3, 4, 5, 6, 7]);


        });
    }
}
