<?php

namespace App\Http\Controllers;

use App\Compuesto;
use App\Producto;
use App\services\ProductoFactory;
use App\services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request){

            return (new Producto())->create([
                'nombre' => $request['nombre'],
                'descripcion' => $request['descripcion'],
                'importe' => $request['importe'],
                'pto_reposicion' => $request['ptoReposicion'],
                'id_ml' => $request['idMl'],
                'id_empresa' => $request['idEmpresa'],
                'componentes' => !isset($request['componentes']) ? null : $request['componentes'],
            ]);
        });
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id){
            $prod = Producto::find($id);
            $prod->fill([
                'nombre' => $request['nombre'],
                'descripcion' => $request['descripcion'],
                'importe' => $request['importe'],
                'pto_reposicion' => $request['ptoReposicion'],
                'id_empresa' => $request['idEmpresa'],
                'componentes' => !isset($request['componentes']) ? null : $request['componentes'],
            ]);
            $prod->save();
        });
    }

    public function find($id)
    {
        $prod = Producto::find($id);

    }

    public function all(Request $request)
    {
        return Producto::where('id_empresa', $request['id_empresa'])->get();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id){
            $prod = Producto::find($id);
            $prod->delete();
        });
    }
}
