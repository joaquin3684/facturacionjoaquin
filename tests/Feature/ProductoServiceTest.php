<?php

namespace Tests\Feature;

use App\Producto;
use App\services\ProductoService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductoServiceTest extends TestCase
{
    use DatabaseMigrations;


    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new ProductoService();
        $this->artisan('migrate:refresh', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'EmpresaSeeder', '--database' => 'mysql_testing']);

    }

    public function testStore()
    {
        $data = factory(Producto::class)->make();
        $this->service->store(
            $data->nombre,
            $data->descripcion,
            $data->importe,
            $data->pto_reposicion,
            $data->id_ml,
            $data->id_empresa
        );

        $this->assertDatabaseHas('productos', $data->toArray());
    }

    public function testUpdate()
    {
        $data = factory(Producto::class)->create();
        $dataUpdate = factory(Producto::class)->make();
        $this->service->update(
            $dataUpdate->nombre,
            $dataUpdate->descripcion,
            $dataUpdate->importe,
            $dataUpdate->pto_reposicion,
            $dataUpdate->id_ml,
            $dataUpdate->id_empresa,
            $data->id
        );

        $this->assertDatabaseHas('productos', $dataUpdate->toArray());
    }

    public function testFind()
    {
        $data = factory(Producto::class)->create();
        $prod = $this->service->find($data->id);
        $this->assertEquals(1, $prod->id);
    }

    public function testAll()
    {
        $data = factory(Producto::class,3)->create();
        $productos = $this->service->all(1);
        $this->assertEquals(3, $productos->count());
    }

    public function testDelete()
    {
        $data = factory(Producto::class)->create();
        $this->service->delete($data->id);
        $this->assertSoftDeleted('productos', ['id' => 1]);
    }
}
