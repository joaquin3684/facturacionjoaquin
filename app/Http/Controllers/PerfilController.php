<?php

namespace App\Http\Controllers;

use App\Perfil;
use Illuminate\Http\Request;
use App\services\PerfilService;

class PerfilController extends Controller
{

    public function all(){
        return Perfil::all();
    }
}
