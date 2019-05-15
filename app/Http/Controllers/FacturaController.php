<?php

namespace App\Http\Controllers;

use App\services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    private $facService;

    public function __construct(FacturaService $facService)
    {
        $this->facService = $facService;
    }

    public function store(Request $request)
    {
        Db::transaction(function() use ($request){
            $this->facService->store(
                $request['cuit_emisor'],
                $request['cuit_receptor'],
                $request['tipo'],
                $request['facturado'],
                $request['ml'],
                $request['items'],
                $request['nro'],
                $request['fecha'],
                $request['idEmpresa'],
                $request['entregado']
            );
        });
    }

    public function update(Request $request, $id)
    {
        Db::transaction(function() use ($request, $id) {
            $this->facService->update(
                $request['cuit_emisor'],
                $request['cuit_receptor'],
                $request['tipo'],
                $request['facturado'],
                $request['ml'],
                $request['items'],
                $request['nro'],
                $request['fecha'],
                $request['idEmpresa'],
                $request['entregado'],
                $id
            );
        });
    }

    public function all(Request $request, $fechaDesde, $fechaHasta)
    {
        return $this->facService->all($fechaDesde, $fechaHasta, $request['idEmpresa']);
    }

    public function find($id)
    {
        return $this->facService->find($id);
    }

    public function delete($id)
    {
        $this->facService->delete($id);
    }
}
