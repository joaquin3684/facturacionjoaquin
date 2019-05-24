<?php

namespace Tests\Feature;

use App\Compuesto;
use App\Producto;
use App\services\ProductoService;
use App\Simple;
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
        $this->artisan('migrate:refresh', ['--database' => 'mysql_testing']);
        $this->artisan('db:seed', ['--class' => 'EmpresaSeeder', '--database' => 'mysql_testing']);

    }

    public function testStore()
    {
        $compuestos = factory(Producto::class, 3)->create();
        $data = factory(Producto::class)->make();

        $this->service->store(
            $data->nombre,
            $data->descripcion,
            $data->importe,
            $data->pto_reposicion,
            $data->id_ml,
            $data->id_empresa,
            $compuestos->map(function($c){return array('idProducto' => $c->id, 'cantidad' => 2);})->toArray()
        );

        $this->assertDatabaseHas('productos', $data->toArray());
        $this->assertDatabaseHas('composicion', ['id_producto' => 4, 'id_compuesto' => 1]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 4, 'id_compuesto' => 2]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 4, 'id_compuesto' => 3]);
    }

    public function testUpdate()
    {
        $compuestos = factory(Producto::class, 3)->create();

        $data = factory(Producto::class)->create();
        $dataUpdate = factory(Producto::class)->make();
        $this->service->update(
            $dataUpdate->nombre,
            $dataUpdate->descripcion,
            $dataUpdate->importe,
            $dataUpdate->pto_reposicion,
            $dataUpdate->id_ml,
            $dataUpdate->id_empresa,
            $compuestos->map(function($c){return array('idProducto' => $c->id, 'cantidad' => 2);})->toArray(),
            $data->id
        );

        $this->assertDatabaseHas('productos', $dataUpdate->toArray());
        $this->assertDatabaseHas('composicion', ['id_producto' => 4, 'id_compuesto' => 1]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 4, 'id_compuesto' => 2]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 4, 'id_compuesto' => 3]);
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

    public function testPrueba()
    {

       $prod = Compuesto::create([]);
        factory(Producto::class)->create(['stock' => 10, 'tipo_type' =>'App\Compuesto', 'tipo_id' => $prod->id]);
        $simple = Simple::create([]);
        $simple2 = Simple::create([]);
        $comp1 = factory(Producto::class)->create(['stock' => 10, 'tipo_type' =>'App\Simple', 'tipo_id' => $simple->id]);
        $comp2 = factory(Producto::class)->create(['stock' => 10, 'tipo_type' =>'App\Simple', 'tipo_id' => $simple2->id]);

        $prod->componentes()->attach([
            $comp1->id => ['cantidad' => 2],
            $comp2->id => ['cantidad' => 2],
        ]);
        $a = $prod->producto->stock;
        $prod->descontarStock(3);

        $prod = $prod->fresh('componentes');
        $stock = $prod->producto->stock;
        $prod2 = Simple::create(
            factory(Producto::class)->make(['stock' => 10])->toArray()

        );

        $prods = Producto::all();
        $prods->each(function($p){
            $p->aumentarStock(2);
        });
        $prod->stock;
    }
}
