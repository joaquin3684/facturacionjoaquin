<?php

namespace App\Http\Controllers;

use App\Publicacion;
use App\services\ML\UserMapper;
use Illuminate\Http\Request;

class PublicacionController extends Controller
{

    public function all(Request $request)
    {
        return Publicacion::where('id_empresa', $request['idEmpresa'])->get();
    }

    public function linkear(Request $request, $idPublicacion)
    {
        $publicacion = Publicacion::find($idPublicacion);
        $publicacion->linkear($request['idProducto']);
    }

    public function deslinkear($idPublicacion)
    {
        $publicacion = Publicacion::find($idPublicacion);
        $publicacion->deslinkear();
    }

    public function cargarPublicacionesMl(Request $request)
    {
        $meli = $request['meli'];
        $params = array('access_token' => $meli->getToken());
        $user = UserMapper::map($meli->get('users/me', $params)['body']);

        $params = array('access_token' => $meli->getToken(), 'status' => 'active', 'sku' => null);
        $items = $meli->get('users/'.$user->id.'/items/search', $params)['body']->results;


        $params = array('access_token' => $meli->getToken(), 'ids' => implode(",",$items));

        //TODO aca falta marcar los primeros veinte y la paginacion
        $items = $meli->get('items', $params)['body'];
        $itemsSinMap = collect($items)->map(function($i){
            return $i->body;
        });
        $publis = PublicacionMapper::map($itemsSinMap);

        return $publis;
    }

}
