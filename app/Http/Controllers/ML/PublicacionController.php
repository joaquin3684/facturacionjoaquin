<?php

namespace App\Http\Controllers;

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
        $params = array('access_token' => $meli->getToken(), 'status' => 'active');
        $publis = PublicacionMapper::map($meli->get('users/'.$user['id'].'/items/search', $params)['body']);

        return $publis;

    }
}
