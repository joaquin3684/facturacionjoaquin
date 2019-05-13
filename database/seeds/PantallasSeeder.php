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
            factory(App\Pantalla::class)->create(['nombre' => 'producto']);
            factory(App\Pantalla::class)->create(['nombre' => 'factura']);

        });

    }
}
