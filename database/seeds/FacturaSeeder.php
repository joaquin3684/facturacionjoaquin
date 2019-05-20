<?php

use Illuminate\Database\Seeder;

class FacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function(){

            $this->call(ProductoSeeder::class);
            factory(\App\Factura::class, 5)->create()->each(function($fac){
                factory(\App\ItemFactura::class, 2)->create(['id_factura' => $fac->id]);
            });



        });
    }
}
