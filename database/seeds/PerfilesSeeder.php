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

            $perfil1 = factory(\App\Perfil::class)->create(['nombre' => 'admin']);



            $perfil1->pantallas()->attach([1,2,3,4,5,6,7,8,9,10,11]);

        });
    }
}
