<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function store(Request $request)
    {
        Db::transaction(function() use ($request){

            $total = array_sum(array_map(function($item){
                return $item['importe'];
            }, $request['items']));

            $impuestos = array_sum(array_map(function($item){
                return $item['impuesto'];
            }, $request['items']));

            (new Venta())->create([
                'cuit_receptor' => $request['cuit_receptor'],
                'total_bruto' => $total,
                'total_impuestos' => $impuestos,
                'total_neto' => $total - $impuestos,
                'tipo' => $request['tipo'],
                'facturado' => $request['facturado'],
                'ml' => $request['ml'],
                'numero' => $request['nro'],
                'fecha' => $request['fecha'],
                'id_empresa' => $request['idEmpresa'],
                'entregado' => $request['entregado']
            ]);

        });
    }


    public function all(Request $request, $fechaDesde, $fechaHasta)
    {
        return Venta::with('items.producto')
            ->where('fecha', '<=', $fechaHasta)
            ->where('fecha', '>=', $fechaDesde)
            ->where('id_empresa', $request['idEmpresa'])
            ->get();
    }

    public function find($id)
    {
        return Venta::with('items.producto')->find($id);
    }

}
