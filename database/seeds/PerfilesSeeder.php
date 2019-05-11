<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function() {

            $this->call(PantallasSeeder::class);

            $perfil1 = factory(\App\Perfil::class)->create(['nombre' => 'ADMIN']);
            $perfil2 = factory(\App\Perfil::class)->create(['nombre' => 'ADMINISTRADOR VENTAS']);
            $perfil3 = factory(\App\Perfil::class)->create(['nombre' => 'CADETE']);
            $perfil4 = factory(\App\Perfil::class)->create(['nombre' => 'ESTADISTICAS']);
            $perfil5 = factory(\App\Perfil::class)->create(['nombre' => 'EXTERNO']);
            $perfil6 = factory(\App\Perfil::class)->create(['nombre' => 'OPERADOR AUDITORIA']);
            $perfil7 = factory(\App\Perfil::class)->create(['nombre' => 'OPERADOR LOGISTICA']);
            $perfil8 = factory(\App\Perfil::class)->create(['nombre' => 'OPERADOR VALIDACION']);
            $perfil9 = factory(\App\Perfil::class)->create(['nombre' => 'OPERADOR VENTA']);
            $perfil10 = factory(\App\Perfil::class)->create(['nombre' => 'PROMOTORA']);
            $perfil11 = factory(\App\Perfil::class)->create(['nombre' => 'SUPERVISOR CALL']);
            $perfil12 = factory(\App\Perfil::class)->create(['nombre' => 'SUPERVISOR LOGISTICA']);
            $perfil13 = factory(\App\Perfil::class)->create(['nombre' => 'SUPERVISOR VENDEDORAS']);
            $perfil14 = factory(\App\Perfil::class)->create(['nombre' => 'VENDEDORA']);
            $perfil15 = factory(\App\Perfil::class)->create(['nombre' => 'TEAM LEADER']);


            $perfil1->pantallas()->attach([1,2,3,4,5,6,7,8,9,10,11]);
            $perfil14->pantallas()->attach([11]);
            $perfil9->pantallas()->attach([11, 8]);
            $perfil15->pantallas()->attach([11]);
        });
    }
}
