<?php

namespace App\Http\Controllers\ML;

use App\Http\Controllers\Controller;
use App\services\ML\PublicacionMapper;
use App\services\ML\UserMapper;
use Illuminate\Http\Request;

class PublicacionController extends Controller
{

    public function publicacionesSinLinkear(Request $request)
    {
        $meli = $request['meli'];
        $params = array('access_token' => $meli->getToken());
        $user = UserMapper::map($meli->get('users/me', $params)['body']);
        $params = array('access_token' => $meli->getToken(), 'status' => 'active', 'sku' => '');
        $items = $meli->get('users/'.$user->id.'/items/search', $params)['body']->results;

        $params = array('access_token' => $meli->getToken(), 'ids' => implode(",",$items));
        $items = $meli->get('items', $params)['body'];
        $itemsSinMap = collect($items['body'])->map(function($i){
            return $i['body'];
        });

        $publis = PublicacionMapper::map($itemsSinMap);

        return $publis;

    }

    public function linkear(Request $request)
    {
        $meli = $request['meli'];
        $idPubli = $request['idPublicacion'];
        $params = array('access_token' => $meli->getToken());
        $meli->put('items/'.$idPubli , ['seller_custom_field' => $request['idProducto']], $params);
    }
}
