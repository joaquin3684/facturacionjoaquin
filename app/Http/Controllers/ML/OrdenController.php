<?php

namespace App\Http\Controllers\ML;

use App\Http\Controllers\Controller;
use App\services\ML\AuthMLService;
use App\services\ML\OrdenMapper;
use App\services\ML\OrdenService;
use App\services\ML\UserMapper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrdenController extends Controller
{

    private $service;


    public function ordenesRecientes(Request $request)
    {
        $meli = $request['meli'];
        $params = array('access_token' => $meli->getToken());
        $user = UserMapper::map($meli->get('users/me', $params)['body']);
        $params = array('access_token' => $meli->getToken(), 'seller' => $user->id);

        $ordenes = OrdenMapper::map($meli->get('orders/search/recent', $params)['body']);
        return $ordenes;
    }



}
