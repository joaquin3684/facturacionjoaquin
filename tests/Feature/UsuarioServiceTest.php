<?php

namespace Tests\Feature;

use App\services\ML\Meli;
use App\services\ML\PublicacionMapper;
use App\services\ML\UserMapper;
use App\services\UsuarioService;
use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsuarioServiceTest extends TestCase
{
    use DatabaseMigrations;


    private $service;
    public function setUp()
    {
        parent::setUp();
        $this->service = new UsuarioService();
        $this->artisan('migrate:refresh', ['--database' => 'mysql_testing']);
        //$this->artisan('db:seed', ['--class' => 'PerfilesSeeder', '--database' => 'mysql_testing']);

    }


    public function testCrearUsuario()
    {
        $usuario = 'pepe';
        $nombre = 'armando';
        $email = 'armando';
        $password = 'armando';
        $perfiles = [1];
        $obrasSociales = [1,2];
        $usuario = $this->service->crear($nombre, $usuario, $password, $email, $perfiles, $obrasSociales);

        $this->assertDatabaseHas('usuarios', ['user' => $usuario->user, 'nombre' => $nombre, 'email' => $email]);
        $this->assertDatabaseHas('usuario_perfil', ['id_usuario' => $usuario->id, 'id_perfil' => 1]);


    }

    public function testUpdateUsuario()
    {

        $user = factory(User::class)->create();

        $nombre = 'armando';
        $email = 'armando';
        $perfiles = [1];
        $obrasSociales = [1,2];

        $this->service->update($nombre, $email, $perfiles, $obrasSociales, $user);

        $this->assertDatabaseHas('usuarios', ['user' => $user->user, 'nombre' => $nombre, 'email' => $email]);
        $this->assertDatabaseHas('usuario_perfil', ['id_usuario' => $user->id, 'id_perfil' => 1]);

    }

    public function testAll()
    {
        factory(User::class)->create(['user' => 'ramon']);
        factory(User::class)->create(['user' => 'ramon2']);
        $users = $this->service->all();
        $this->assertEquals(2, $users->count());
    }

    public function testfind()
    {
        factory(User::class)->create(['user' => 'ramon']);
        factory(User::class)->create(['user' => 'ramon2']);
        $user = $this->service->find(1);
        $this->assertEquals('ramon', $user->user);
    }

    public function testdelete()
    {
        $user = factory(User::class)->create(['user' => 'ramon']);
        factory(User::class)->create(['user' => 'ramon2']);
        $this->service->delete($user);
        $this->assertSoftDeleted('usuarios', ['id' => 1]);
    }

    public function testCambiarPassword()
    {
        $user = factory(User::class)->create(['user' => 'ramon']);
        $user = $this->service->cambiarPassword('mantecol', $user);
        $this->assertTrue(Hash::check('mantecol', $user->password));
    }

    public function testML()
    {
        $meli = new Meli(1);
        $params = array('access_token' => 'APP_USR-2382179841161472-052118-931b7819456f7942c53b5c67a233d75a-436820632');
        $user = UserMapper::map($meli->get('users/me', $params)['body']);
        $params = array('access_token' => 'APP_USR-2382179841161472-052118-931b7819456f7942c53b5c67a233d75a-436820632', 'status' => 'active', 'sku' => null);
        $items = $meli->get('users/'.$user->id.'/items/search', $params)['body']->results;

        $params = array('access_token' => 'APP_USR-2382179841161472-052118-931b7819456f7942c53b5c67a233d75a-436820632', 'ids' => implode(",",$items));
        //TODO aca falta marcar los primeros veinte y la paginacion
        $items = $meli->get('items', $params)['body'];
        $itemsSinMap = collect($items)->map(function($i){
            return $i->body;
        });
        $publis = PublicacionMapper::map($itemsSinMap);

        return $publis;
    }
}
