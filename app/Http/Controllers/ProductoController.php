<?php

namespace App\Http\Controllers;

use App\services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    private $productoService;

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request){
            $this->productoService->store(
                $request['nombre'],
                $request['descripcion'],
                $request['importe'],
                $request['ptoReposicion'],
                $request['idMl'],
                $request['idEmpresa']
            );
        });
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id){
            $this->productoService->update(
                $request['nombre'],
                $request['descripcion'],
                $request['importe'],
                $request['ptoReposicion'],
                $request['idMl'],
                $request['idEmpresa'],
                $id
            );
        });
    }

    public function find($id)
    {
        return $this->productoService->find($id);
    }

    public function all(Request $request)
    {

        return $this->productoService->all($request->id_empresa);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id){
            $this->productoService->delete($id);
        });
    }
}
