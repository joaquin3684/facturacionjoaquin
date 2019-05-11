<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\PerfilService;

class PerfilController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new PerfilService();
    }

    public function all(){
        return $this->service->all();
    }
}
