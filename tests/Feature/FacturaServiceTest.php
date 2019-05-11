<?php

namespace Tests\Feature;

use App\Factura;
use App\ItemFactura;
use App\services\FacturaService;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacturaServiceTest extends TestCase
{
    use DatabaseMigrations;


    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new FacturaService();
        $this->artisan('migrate:refresh', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'EmpresaSeeder', '--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'ProductoSeeder', '--database' => 'mysql_testing']);
    }

    public function testStore()
    {
        $cuitEmisor = '20396245217';
        $cuitReceptor = '20145678985';
        $tipo = 'A';
        $facturado = 1;
        $ml = 1;
        $items = factory(ItemFactura::class,3)->make()->toArray();
        $nro = 321456987;
        $fecha = Carbon::today()->toDateString();
        $idEmpresa = 1;
        $this->service->store($cuitEmisor, $cuitReceptor, $tipo, $facturado, $ml, $items, $nro, $fecha, $idEmpresa);

        $this->assertDatabaseHas('facturas', [
            'cuit_emisor' => $cuitEmisor,
            'cuit_receptor' => $cuitReceptor,
            'tipo' => $tipo,
            'facturado' => $facturado,
            'ml' => $ml,
            'numero' => $nro,
            'fecha' => $fecha,
            'id_empresa' => $idEmpresa
        ]);
        $this->assertDatabaseHas('items_factura', $items[0]);
        $this->assertDatabaseHas('items_factura', $items[1]);
        $this->assertDatabaseHas('items_factura', $items[2]);
    }

    public function testUpdate()
    {
        $factura = factory(Factura::class)->create();
        $cuitEmisor = '20396245217';
        $cuitReceptor = '20145678985';
        $tipo = 'A';
        $facturado = 1;
        $ml = 1;
        $items = factory(ItemFactura::class,3)->make(['id_factura' => $factura->id +1])->toArray();
        $nro = 321456987;
        $fecha = Carbon::today()->toDateString();
        $idEmpresa = 1;

        $this->service->update($cuitEmisor, $cuitReceptor, $tipo, $facturado, $ml, $items, $nro, $fecha, $idEmpresa, $factura->id);

        $this->assertDatabaseHas('facturas', [
            'cuit_emisor' => $cuitEmisor,
            'cuit_receptor' => $cuitReceptor,
            'tipo' => $tipo,
            'facturado' => $facturado,
            'ml' => $ml,
            'numero' => $nro,
            'fecha' => $fecha,
            'id_empresa' => $idEmpresa
        ]);
        $this->assertDatabaseHas('items_factura', $items[0]);
        $this->assertDatabaseHas('items_factura', $items[1]);
        $this->assertDatabaseHas('items_factura', $items[2]);
    }

    public function testAll()
    {
        factory(Factura::class,3)->create();
        $facturas = $this->service->all(Carbon::today()->toDateString(), Carbon::today()->toDateString(), 1);
        $this->assertEquals(3, $facturas->count());
    }

    public function testFind()
    {
        $fac = factory(Factura::class)->create();
        $this->assertEquals(1, $fac->id);
    }

    public function testDelete()
    {
        $fac = factory(Factura::class)->create();
        $this->service->delete($fac->id);
        $this->assertDatabaseMissing('facturas', ['id' => $fac->id]);
    }
}
