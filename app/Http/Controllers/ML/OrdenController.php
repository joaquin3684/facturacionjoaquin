<?php

namespace App\Http\Controllers\ML;

use App\Http\Controllers\Controller;
use App\services\ML\AuthMLService;
use App\services\ML\OrdenService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrdenController extends Controller
{

    private $service;


    public function ordenes(Request $request)
    {
        $srv = new OrdenService($request['meli']);
        return $srv->ordenesRecientes();
    }



}
