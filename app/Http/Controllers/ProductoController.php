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
                'id_ml' => $request['idMl'],
                'id_empresa' => $request['idEmpresa'],
                'compuestos' => $request['compuestos']
            ]);
            $prod->save();
        });
    }

    public function find($id)
    {
        return Producto::with('tipo.componentes')->find($id);

    }

    public function all(Request $request)
    {
        $com =  Producto::with('tipo.componentes')->where('tipo_type', 'App\Compuesto')->where('id_empresa', $request['idEmpresa'])->get();
        $simp =  Producto::with('tipo')->where('tipo_type', 'App\Simple')->where('id_empresa', $request['idEmpresa'])->get();
        return $com->union($simp);
        return Producto::with(['tipo.componentes' => function($q){
           return $q->where('productos.tipo_type', 'App\Compuesto');
       }]);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id){
            $prod = Producto::find($id);
            $prod->delete();
        });
    }
}
