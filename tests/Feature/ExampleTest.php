<?php

namespace Tests\Feature;

use App\Enums\Estados;
use App\Venta;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */    public function setUp()
{
    parent::setUp();


}
    public function testBasicTest()
    {
        $venta = Venta::whereDoesntHave('estados', function ($q) {
            $q->where('estado', Estados::VALIDADO)
                ->orWhere('estado', 'like', 'Rech%');
        })->with('estados', 'obraSocial')->get();

        $venta->toArray();

        $a=2;
    }
}
