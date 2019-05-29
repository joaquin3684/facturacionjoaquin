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

    public function testStoreProdSimple()
    {
        $data = factory(Producto::class)->make();

        $data['componentes'] = null;
        $prod = new Producto();
        $prod = $prod->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'importe' => $data['importe'],
            'pto_reposicion' => $data['pto_reposicion'],
            'id_empresa' => 1,
            'componentes' => !isset($data['componentes']) ? null : $data['componentes'],
        ]);

        unset($data['componentes']);
        $this->assertEquals($prod->nombre, $data['nombre']);
        $this->assertDatabaseHas('simples', ['id' => 1]);

    }

    public function testStoreProdCompuesto()
    {
        factory(Producto::class,2)->create();
        $data = factory(Producto::class)->make();

        $data['componentes'] = [['idProducto' => 1, 'cantidad' => 3],['idProducto' => 2, 'cantidad' => 4]];
        $prod = new Producto();
        $prod = $prod->create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'importe' => $data['importe'],
            'pto_reposicion' => $data['pto_reposicion'],
            'id_empresa' => 1,
            'componentes' => !isset($data['componentes']) ? null : $data['componentes'],
        ]);

        unset($data['componentes']);

        $this->assertEquals($prod->nombre, $data['nombre']);
        $this->assertDatabaseHas('compuestos', ['id' => 1]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 1, 'id_componente' => 1]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 1, 'id_componente' => 2]);
    }


    public function testUpdateManteniendoTipo()
    {
        $this->artisan('db:seed', ['--class' => 'ProductoSeederTest', '--database' => 'mysql_testing']);

        $dataUpdate = factory(Producto::class)->make();

        $prod = Producto::find(3);
        $prod->fill([
            'nombre' => $dataUpdate['nombre'],
            'descripcion' => $dataUpdate['descripcion'],
            'importe' => $dataUpdate['importe'],
            'pto_reposicion' => $dataUpdate['pto_reposicion'],
            'id_empresa' => 1,
            'componentes' => !isset($dataUpdate['componentes']) ? null : $dataUpdate['componentes'],
        ]);

        $prod->save();

        $this->assertEquals($prod->nombre, $dataUpdate['nombre']);
        $this->assertDatabaseHas('simples', ['id' => 1]);

    }

    public function testUpdateCambiandoTipo()
    {
        $this->artisan('db:seed', ['--class' => 'ProductoSeederTest', '--database' => 'mysql_testing']);

        $dataUpdate = factory(Producto::class)->make();
        $prod = Producto::find(1);
        $prod->fill([
            'nombre' => $dataUpdate['nombre'],
            'descripcion' => $dataUpdate['descripcion'],
            'importe' => $dataUpdate['importe'],
            'pto_reposicion' => $dataUpdate['pto_reposicion'],
            'id_empresa' => 1,
            'componentes' => !isset($dataUpdate['componentes']) ? null : $dataUpdate['componentes'],
        ]);

        $prod->save();

        $this->assertEquals($prod->nombre, $dataUpdate['nombre']);
        $this->assertDatabaseHas('simples', ['id' => 3]);
        $this->assertSoftDeleted('compuestos', ['id' => 1]);


        $dataUpdate = factory(Producto::class)->make();
        $dataUpdate['componentes'] = [['idProducto' => 1, 'cantidad' => 3],['idProducto' => 2, 'cantidad' => 2]];
        $prod = Producto::find(3);
        $prod->fill([
            'nombre' => $dataUpdate['nombre'],
            'descripcion' => $dataUpdate['descripcion'],
            'importe' => $dataUpdate['importe'],
            'pto_reposicion' => $dataUpdate['pto_reposicion'],
            'id_empresa' => 1,
            'componentes' => !isset($dataUpdate['componentes']) ? null : $dataUpdate['componentes'],
        ]);

        $prod->save();

        $this->assertEquals($prod->nombre, $dataUpdate['nombre']);
        $this->assertSoftDeleted('simples', ['id' => 1]);
        $this->assertDatabaseHas('compuestos', ['id' => 3]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 3, 'id_componente' => 1]);
        $this->assertDatabaseHas('composicion', ['id_producto' => 3, 'id_componente' => 2]);
    }


    public function testDelete()
    {
        $this->artisan('db:seed', ['--class' => 'ProductoSeederTest', '--database' => 'mysql_testing']);

        $prod = Producto::find(1);
        $prod->delete();
        $this->assertSoftDeleted('productos', ['id' => 1]);
        $this->assertSoftDeleted('compuestos', ['id' => 1]);
    }


}
