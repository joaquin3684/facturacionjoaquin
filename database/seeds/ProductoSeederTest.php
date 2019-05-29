<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeederTest extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::transaction(function(){

            $comp = \App\Compuesto::create([]);
            $superComp = \App\Compuesto::create([]);
            \App\Simple::create([]);
            \App\Simple::create([]);
           $compuesto = factory(\App\Producto::class)->create(['nombre' => 'compuesto', 'tipo_type' => 'App\Compuesto', 'tipo_id' => 1]);
           $superCompuesto = factory(\App\Producto::class)->create(['nombre' => 'super compuesto', 'tipo_type' => 'App\Compuesto', 'tipo_id' => 2]);

           $simple = factory(\App\Producto::class)->create(['nombre' => 'simple 1', 'tipo_type' => 'App\Simple', 'tipo_id' => 1, 'stock' => 20]);
           $simple2 = factory(\App\Producto::class)->create(['nombre' => 'simple 2', 'tipo_type' => 'App\Simple', 'tipo_id' => 2, 'stock' => 20]);

           $comp->componentes()->attach([
               $simple->id => ['cantidad' => 2],
               $simple2->id => ['cantidad' => 3],
           ]);

           $superComp->componentes()->attach([
               $compuesto->id => ['cantidad' => 3],
               $simple2->id => ['cantidad' => 2],
           ]);

        });
    }
}
