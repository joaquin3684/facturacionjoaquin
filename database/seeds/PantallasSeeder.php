<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PantallasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Db::transaction(function(){
            factory(App\Pantalla::class)->create(['nombre' => 'administracionVenta']);
            factory(App\Pantalla::class)->create(['nombre' => 'auditoria']);
            factory(App\Pantalla::class)->create(['nombre' => 'borrar']);
            factory(App\Pantalla::class)->create(['nombre' => 'estadistica']);
            factory(App\Pantalla::class)->create(['nombre' => 'logistica']);
            factory(App\Pantalla::class)->create(['nombre' => 'logisticaOper']);
            factory(App\Pantalla::class)->create(['nombre' => 'modificar']);
            factory(App\Pantalla::class)->create(['nombre' => 'recuperarVenta']);
            factory(App\Pantalla::class)->create(['nombre' => 'usuario']);
            factory(App\Pantalla::class)->create(['nombre' => 'validacion']);
            factory(App\Pantalla::class)->create(['nombre' => 'venta']);
        });

    }
}
